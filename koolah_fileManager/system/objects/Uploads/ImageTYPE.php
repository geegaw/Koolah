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

}