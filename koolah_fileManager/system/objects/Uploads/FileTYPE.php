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
    
    public function getType(){
        if ( $this->isImage() )
            return 'img';
        if ( $this->isDoc() )
            return 'doc';
        if ( $this->isVid() )
            return 'vid';
        if ( $this->isAudio() )
            return 'audio';
        return 'file';
    }
    
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
    
    public function getExt(){ return $this->getExtFromFileName(); }
    public function getExtFromFileName( $filename=null ){
        if ( !$filename )
            $filename = $this->filename;
        
        $ext = explode('.', $filename);
        $ext = strtolower( $ext[ (count($ext) -1) ] );
        return $ext;
    }
    
}