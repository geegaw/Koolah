<?php
class PageTYPE extends Node{
    public $label;
    public $seo;
    
    private $publicationStatus;
    private $templateID;
    private $data;
        
    public function __construct( $db=null ){
        parent::__construct( $db, PAGES_COLLECTION );
        $this->label = new LabelTYPE($db, PAGES_COLLECTION);
        $this->publicationStatus = 'draft';
        $this->templateID = null;
        $this->data = null;
        $this->seo = new SeoTYPE();   
    }
    
    public function __get( $suspect ){
        if ( $suspect == 'url' )
            return $this->getUrl();
        
        if (isset( $this->data[$suspect] ))    
            return $this->data[$suspect];
        return null;
    }
    
    //GETTERS
    public function getPublicationStatus(){ return $this->publicationStatus; }
    public function getTemplateID(){ return $this->templateID; }
    public function getData(){ return $this->data; }
    public function getAliases(){ return $this->seo->getAliases(); }
    public function getUrl(){
        $aliases = $this->getAliases();
        $alias = end($aliases);
        return $alias->getAlias();
    }
    
    
    //SETTERS
    public function setPublicationStatus($status){ $this->publicationStatus=$status; }
    public function setTemplateID($templateID){ $this->templateID=$templateID; }
    public function setData($data){ $this->data=$data; }  
    
    
    //bools
    public function isPublished(){
        return $this->publicationStatus == 'published';
    }
    
    
    //fetchers
    /*
    public function getByID( $id=null ){
        parent::getByID( $id );
        $this->fetchAliases();
    }  
     */   
    public function get( $q, $fields=null ){
        parent::get( $q, $fields );
        $this->fetchAliases();
    }
    
    public function getTemplateFile(){
        $file = '';    
        $template = new TemplateTYPE();
        $template->getByID( $this->templateID );
        if ( $template->getID() )
            $file = $template->label->getRef();
        return $file;
    }
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array( 
           'publicationStatus'=>$this->publicationStatus, 
           'templateID'=>$this->templateID,
           'data'=>$this->data,
           'seo' =>  $this->seo->prepare(),
         );
        return parent::prepare() + $bson + $this->label->prepare();
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
//debug::printr($bson, 1);        
        $this->label->read($bson);
        if ( isset($bson['publicationStatus']) )
            $this->publicationStatus = $bson['publicationStatus'];
        if ( isset($bson['templateID']) )
            $this->templateID = $bson['templateID'];
        if ( isset($bson['data']) )
            $this->data = $bson['data'];
        $this->seo->read($bson['seo']);
    }
    
    public function readObj( $obj ){
//debug::vardump($obj, 1);        
        if ( $obj ){
            $this->label->read($obj->label);    
            $this->publicationStatus = $obj->publicationStatus;               
            $this->templateID = $obj->templateID;
            $this->data = $obj->data;
            $this->seo->read( $bson->seo, $this->getID() );
        }    
    }
    
}

?>
