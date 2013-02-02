<?php
class  ImageTYPE extends Node{
    //CONST
    const CUSTOM_CROP_DIRNAME = 'custom';
    
    //PRIVATE
    public $file;
    public $ratio;
    public $crop;
    
    //CONSTRUCT
    public function __construct( $db=null ){
        parent::__construct( $db, IMAGES_COLLECTION );
        
        $this->file = new FileTYPE($db);
        $this->ratio = new RatioTYPE($db);
        $this->crop = new CropTYPE();        
    }
    
    //BOOL
    public function isModified(){
        if ( !$this->getByID() )
            return true;
        
        $orig = new ImageTYPE();
        $orig->getByID( $this->getByID() );
        
        return md5( $this->crop ) === md5( $orig->crop );    
    }
    
    //fetchers
    
    public function save($bson=null){
        $status = new StatusTYPE();    
        if ( $this->isModified() ){   
            $status = parent::save($bson);
            if ( $status->success() ){
                $path = $this->getPath();
                if ( $this->ratio->getID()  ){
                    foreach( $this->ratio->sizes->sizes as $size ){
                        $filename = $path.'/'.$size->label->getRef().'.'.$this->file->getExt();
//echo $filename.'<br />';
                        $status = $this->crop( $filename, $size->w, $size->h );
                        if ( !$status->success() )
                            return $status;
                    }
                }
                else{
                    $filename = $path.='/'.$this->id.'.'.$this->file->getExt();
                    $status = $this->crop( $filename, $this->crop->w, $this->crop->h );
                }
            }
        }
        return $status;
    }    
    
    private function crop( $image, $w, $h ){
        $status = $this->copyFile( $this->file->getFull_Filename(),  $image);        
        
        if ( $status->success() ){
            $new_image = imagecreatetruecolor($w, $h);            
            
            switch( $this->file->getExtFromFileName() ){
                case 'png':
                    $image_o = imagecreatefrompng($image);
                    $createNew = 'imagepng';
                    break; 
                case 'gif':
                    $image_o = imagecreatefromgif($image);
                    $createNew = 'imagegif';
                    break; 
                default:
                    $image_o = imagecreatefromjpeg($image);
                    $createNew = 'imagejpeg';
                    break;
            }          
            
            try{
                imagecopyresampled(
                    $new_image, 
                    $image_o, 
                    0,
                    0, 
                    $this->crop->coords->x, 
                    $this->crop->coords->y, 
                    $w, 
                    $h, 
                    ($this->crop->coords->x2 - $this->crop->coords->x), 
                    ($this->crop->coords->y2 - $this->crop->coords->y) 
                );
                $createNew($new_image, $image);
                imagedestroy($new_image);
            }
            catch(exception $e){
                $status->setFalse( 'error cropping image' );
            }
        }
        return $status;
    }
    
    private function copyFile($oldFile, $newFilename){
        $status = new StatusTYPE();        
        if ( file_exists($newFilename) ){
            if( !unlink($newFilename) ){
                $status->setFalse( 'could not copy file' );
                return $status;
            }
        }
        
        if ( !copy($oldFile, $newFilename ) )
            $status->setFalse( 'could not copy file' );        
        return $status;
    }
    
    private function getPath(){
        $filename = $this->file->getFull_Filename();
        $ext = $this->file->getExtFromFileName();
        $filename = str_replace(".$ext", '', $filename);
        
        if ( $this->ratio->getID() ){
            $path = $this->file->getPath().'/'.$this->ratio->label->getRef();
            if ( !cmsToolKit::checkDir( $path ) )
                return null;
        }
        else{
            $path = $this->file->getPath().'/'.self::CUSTOM_CROP_DIRNAME;
            if ( !cmsToolKit::checkDir( $path ) )
                return null;
        }        
        return $path;        
    }
    
    /***
     * MONGO FUNCTIONS
     */
     public function prepare(){
         $bson = array(
            "file" => $this->file->getID(),
            "ratio" => $this->ratio->getID(),
            "crop" => $this->crop->prepare(),
         );
         return parent::prepare() + $bson;
    }
    
    public function read( $bson ){
        parent::read($bson);    
        if ( is_array($bson) )
            self::readAssoc($bson);
        elseif( is_object($bson) )
            self::readObj( $bson );
        elseif( is_string($bson) )
            $this->readJSON( $bson );
        else 
            // TODO return error
            return;  
    }
    
    public function readAssoc( $bson ){
//debug::printr($bson);        
        if (isset($bson['file'])){
            $this->file->setID( $bson['file'] );
            $this->file->getByID();
        }
        else {
            $this->file = new FileTYPE( $this->db );
        }
        if (isset($bson['ratio'])  && !empty($bson['ratio'])){
            $this->ratio->setID( $bson['ratio'] );
            $this->ratio->getByID();
        }
        else {
                $this->ratio = new RatioTYPE( $this->db );
            }
        if (isset($bson['crop']))
            $this->crop->read( $bson['crop'] );
//debug::printr($bson['ratio'] );
            //debug::printr($this->ratio, true);
            //debug::printr($this->crop, true);        
    }
    
    public function readObj( $obj ){
//debug::vardump($obj, 1);        
        if ( $obj ){
            if (!empty($obj->file)){    
                $this->file->setID( $obj->file );
                $this->file->getByID();
            }
            else {
                $this->file = new FileTYPE( $this->db );
            }
            if (!empty($obj->ratio)){
                $this->ratio->setID( $obj->ratio );
                $this->ratio->getByID();
            }
            else {
                $this->ratio = new RatioTYPE( $this->db );
            }
            
            $this->crop->read( $obj->crop );
        }    
    }
    
    public function del(){
        $status = new StatusTYPE();
        $path = $this->getPath();
                
        if ( $this->ratio->getID()  ){
            foreach( $this->ratio->sizes->sizes as $size ){
                $filename = $path.'/'.$size->label->getRef().'.'.$this->file->getExt();
                if ( !unlink( $filename ) ){
                    $status->setFalse( 'could not delete crop file' );
                    return $status;
                }   
            }
        }
        else{
            $filename = $path.='/'.$this->id.'.'.$this->file->getExt();
            if ( !unlink( $filename ) ){
                $status->setFalse( 'could not delete crop file' );
                return $status;
            }
        } 
        
        if ( rmdir( $path ) )
            $status =  parent::del();
        else
            $status->setFalse('could not delete crop folder');    
        
        return $status;
    }
}