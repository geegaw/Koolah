<?php
/**
 * ImageTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ImageTYPE
 * 
 * class to deal iamge files
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Uploads
 */
class  ImageTYPE extends Node{
    //CONST
    const CUSTOM_CROP_DIRNAME = 'custom';
    
    /**
     * original file
     * @var FileTYPE
     * @access public
     */
    public $file;
    
    /**
     * ratio image is using
     * @var RatioTYPE
     * @access public
     */
    public $ratio;
    
    /**
     * crop image is using
     * @var CropTYPE
     * @access public
     */
    public $crop;
    
    /**
     * constructor
     * initiates db to the images collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, IMAGES_COLLECTION );
        
        $this->file = new FileTYPE($db);
        $this->ratio = new RatioTYPE($db);
        $this->crop = new CropTYPE();        
    }
    
    /**
     * isModified
     * calculate whether an image has been 
     * modified by a crop
     * @access public   
     * @return bool     
     */    
    public function isModified(){
        if ( !$this->getID() )
            return true;
        
        $orig = new ImageTYPE();
        $orig->getByID( $this->getID() );
        
        return !$this->crop->equals( $orig->crop );
    }
    
    /**
     * save
     * save the crop to the db, crop image based on ratios
     * @access public   
     * @param assocArray $bson
     * @return StatusTYPE     
     */    
    public function save($bson=null){
        $status = new StatusTYPE();   
        if ( $this->isModified() ){
            parent::save($bson);   
            if ( $status->success() ){
                $path = $this->getPath();
                if ( $this->ratio->getID()  ){
                    foreach( $this->ratio->sizes->sizes as $size ){
                        $filename = $path.'/'.$size->label->getRef().'.'.$this->file->getExt();
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
    
    /**
     * crop
     * resize an image based on desired width and height
     * @access private   
     * @param image $image
     * @param int $w
     * @param int $h
     * @return StatusTYPE     
     */    
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
    
    /**
     * copyFile
     * copy a file and rename/move it
     * @access private   
     * @param string $oldFile
     * @param string $newFilename
     * @return StatusTYPE     
     */    
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
    
    /**
     * getPath
     * get the path of a file, and make folders
     * based on ratio names
     * @access private   
     * @return string     
     */    
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
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
         $bson = array(
            "file" => $this->file->getID(),
            "ratio" => $this->ratio->getID(),
            "crop" => $this->crop->prepare(),
         );
         return parent::prepare() + $bson;
    }
    
    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
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
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
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
    
    /**
     * del
     * delete file from db, remove all files from file system
     * @access public   
     * @return StatusTYPE     
     */    
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
    
    /**
     * isLandscapre
     * return true if photo is landscape
     * @access public   
     * @param string $id
     * @return bool     
     */    
    static public function isLandscapre($id){
        $suspect = new ImageTYPE();
        $suspect->getByID($id);
        
        if ($suspect->getID()){
            $filepath = $suspect->file->getFull_Filename();
            list($width, $height, $type, $attr) = getimagesize($filepath);
            
            return $width > $height;
        }
        
        return false; 
    }
}