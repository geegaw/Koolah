<?php
class FileTYPE extends Node{
    //PUBLIC
    public $label;
    public $alt = '';  
    public $description = '';  
    public $tags;
    
    //PRIVATE
    private $templateType;
    private $filename = '';
    private $crops = null;
    
    //CONSTRUCT
    public function __construct( $db=null ){
        parent::__construct( $db, UPLOADS_COLLECTION );
        $this->label = new LabelTYPE( $db, UPLOADS_COLLECTION );
        $this->label->label = 'New File';
        $this->tags = new TagsTYPE($db);
    }
    
    //fetchers
    public function getCrops(){
        $this->crops = new ImagesTYPE( $this->db );
        $this->crops->get( array('file'=>$this->getID() ));        
    }
    
    
    public function save($bson=null){
        $bson = $this->prepare(true);
        return parent::save($bson);
    }
    
    //Bools
    public function isImage($ext=null){
        global $VALID_IMAGES;
        if (!$ext)
            $ext = $this->getExtFromFileName();  
        return (in_Array( $ext, $VALID_IMAGES )); 
    }
    public function isDoc($ext=null){
        global $VALID_DOCS;
        if (!$ext)
            $ext = $this->getExtFromFileName();  
        return (in_array( $ext, $VALID_DOCS )); 
    }
    public function isVid($ext=null){
        global $VALID_VIDS;
        if (!$ext)
            $ext = $this->getExtFromFileName();  
        return (in_array( $ext, $VALID_VIDS )); 
    }
    public function isAudio($ext=null){
        global $VALID_AUDIO;
        if (!$ext)
            $ext = $this->getExtFromFileName();  
        return (in_array( $ext, $VALID_AUDIO )); 
    }
    public function isValidType( $ext=null ){
        global $VALID_FILES;
        if (!$ext)
            $ext = $this->getExtFromFileName();  
        return (in_array( $ext, $VALID_FILES )); 
    }
    public function isValidSize( $size ){ return $size <=  MAX_FILE_SIZE;}
    public function isValid(  $size, $ext=null ){ return $this->isValidType($ext) && $this->isValidSize($size); }
    
    //Getters
    public function getPublic_Filename(){
        $filename = '';
        if ( $this->filename )
            $filename = str_replace(UPLOADS_DIR, '', $this->filename);
        return $filename;
    }
    
    public function getFull_Filename(){ return $this->filename; }
    
    public function getPath(){
         $ext = $this->getExt();
         $filename = $this->id.'.'.$ext;
         return str_replace( $filename, '', $this->filename ); 
    }
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare($forSave=false){
        $bson = array( 
           'alt'=>$this->alt,
           'description'=>$this->description,
           'tags'=>$this->prepareTags(),
         );
         if ( $forSave )
            $bson['filename'] = $this->filename;
         else{ 
             $bson['filename'] = $this->getPublic_Filename();
             if ( $this->isImage() ){
                 $this->getCrops();
                 $bson['crops'] = $this->prepareCrops();
             }
         }
         return parent::prepare() + $bson + $this->label->prepare();
    }
    
    private function prepareTags(){
        $tags = null;
        if ( $this->tags->tags() ){
            foreach($this->tags->tags() as $tag){
                $tags[] = array( 'id'=>$tag->getID(), 'label'=>$tag->label->label );
            }
        }
        return $tags;
    }
    
    private function prepareCrops(){
        $crops = null;
        if ( $this->crops->images() ){
            foreach($this->crops->images() as $crop){
                if ( $crop->ratio->getID() )
                    $label = $crop->ratio->label->label;
                else
                    $label = $crop->crop->w.' x '.$crop->crop->h;
                $bson = $crop->prepare();
                $bson['label'] = $label;
                $crops[] = $bson;
                /*
                $crops[] = array(
                    'id' => $crop->getID(),
                    'label' => $label,
                );
                 * */
            }
        }
        return $crops;
    }
    
    public function read( $bson ){
        parent::read($bson);    
        $this->data = null;
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
//debug::printr($bson, true);        
        $this->label->read($bson);
        if (isset($bson['alt']))
            $this->alt = $bson['alt'];
        if (isset($bson['description']))
            $this->description = $bson['description'];
        if (isset($bson['filename']))
            $this->filename = $bson['filename'];
        $this->readTags($bson['tags']);
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            $this->label->read($obj->label);    
            $this->alt = $bson->alt;
            $this->description = $bson->description;
            $this->filename = $bson->filename;
            $this->readTags($bson->tags);
        }    
    }
    
    private function readTags( $tags ){
        //debug::printr($tags, 1);
        $this->tags->clear();
        if ($tags){
            foreach( $tags as $tag ){
                if (is_object($tag))
                    $id = $tag->id;
                elseif(is_array($tag))
                    $id = $tag['id'];
                else 
                    $id = $tag;
                if ( $id ){
                    $tag = new TagTYPE();
                    $tag->getByID($id);
                    $this->tags->append( $tag );
                }
            }
        }
    }
    
    public function upload( $file ){
        global $VALID_FILES;
        $status = new StatusTYPE();
        if ( $this->getID()  && $file ){
            if ( $ext = $this->getExtFromFileName($file['name']) ){
                if ( in_array( $ext, $VALID_FILES ) ){
                    if ( $file["name"] <= MAX_FILE_SIZE ){
                        $this->filename = $this->setDestination().'/'.$this->id.'.'.$ext;
                        if ( @move_uploaded_file($file['tmp_name'], $this->filename) )
                            $status = $this->save();
                        else
                            $status->setFalse('could not  move file');
                    }
                    else
                        $status->setFalse = 'file is too large';
                }
                else
                    $status->setFalse = 'not a valid file extension';
            }
            else
                $status->setFalse = 'could not get extension';
        }
        else
            $status->setFalse('Missing File or  ID');            
        return $status;        
    }
    
    public function getExt(){ return $this->getExtFromFileName(); }
    public function getExtFromFileName( $filename=null ){
        if ( !$filename )
            $filename = $this->filename;
        
        $ext = explode('.', $filename);
        $ext = strtolower( $ext[ (count($ext) -1) ] );
        return $ext;
    }
    
    private function setDestination( $force=false ){
        if ( (!$this->filename || !$force) && $this->id  ){
                $path = UPLOADS_DIR;
                
                $structure = str_split(UPLOAD_FOLDER_STRUCTURE);
                foreach( $structure as $unit ){
                    $path.= '/'.date($unit);
                    if (!cmsToolKit::checkDir( $path )) 
                        return null;
                }
                
                $path.= '/'.$this->id;
                if (cmsToolKit::checkDir( $path ))
                    return $path;
        }                              
        return null; 
    }
    
    public function del(){
        $status = new StatusTYPE();
        if ( $this->isImage() ){
            $this->getCrops();
            foreach( $this->crops->images() as $crop ){
                $status = $crop->del();
                if ( !$status->success() )
                    return $status;
            }
        }
        if ( unlink( $this->filename ) ){
            if ( rmdir( $this->getPath() ) )
                $status =  parent::del();
            else
                $status->setFalse('could not delete folder');    
        }
        else
            $status->setFalse('could not file');
        
        return $status;
    }
    
}