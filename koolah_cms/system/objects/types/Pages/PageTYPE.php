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
    
    //GETTERS
    public function getPublicationStatus(){ return $this->publicationStatus; }
    public function getTemplateID(){ return $this->templateID; }
    public function getData(){ return $this->data; }
    public function getAliases(){ return $this->seo->getAliases(); }
    
    //SETTERS
    public function setPublicationStatus($status){ $this->publicationStatus=$status; }
    public function setTemplateID($templateID){ $this->templateID=$templateID; }
    public function setData($data){ $this->data=$data; }  
    
    
    public function mkInput(){
        $input = '';    
        return $input;
    }
    
    public function save($bson=null){
        $status = $this->preSave();
        if ( !$status->success() )
            return $status;
        
        $status = parent::save($bson);     
        if ( !$status->success() )
            return $status;
        
        return $this->postSave();
    }
    
    private function preSave(){
        if ( !$this->seo->title ){
            $this->seo->title =  $this->label->label;
        }
        if ( !count( $this->getAliases()) ){
            $template = new TemplateTYPE();
            $template->getByID( $this->templateID );
            $url = '/'.$template->label->label.'/'.$this->seo->title;
            $alias = new AliasTYPE(null, $url, $this->getID() );
            $this->seo->getAliasesInstance()->append( $alias );
        }
        
        return $this->saveAliases();
    }
    
    private function postSave(){
        $status = new StatusTYPE();
        //$status = $this->saveAliases();
        return $status;
    }
    
    private function saveAliases(){
        $status = new StatusTYPE();    
        if ( count($this->getAliases()) ){
            $toDelete = $this->getAliasesToDelete();
            if ( $toDelete->length() )
                $status = $toDelete->del();
            
            if ( $status->success() ){
                foreach( $this->getAliases() as $alias ){
                    if ( !$alias->getID() ){
                        $alias->setPageID( $this->getID() ); 
                        $sStatus = $alias->save();
                        if ( !$sStatus->success() )
                            $status->setFalse( 'errors occurred while saving aliases' );
                    }
                }
            }   
        }
        return $status;
    }
    
    private function getAliasesToDelete(){
        $toDelete = new AliasesTYPE();;
            
        $origAliases = new AliasesTYPE();
        $origAliases->get( array('pageID'=>$this->getID()) );
        
        if ( !count($origAliases->aliases()) )
            return $toDelete;
        
        //debug::printr($origAliases, 1);
        if (count($this->getAliases()) ){
            foreach( $origAliases->aliases() as $alias ){
                $nodes = $this->seo->getAliasesInstance()->find( $alias->getAlias() );
                if ( empty($nodes) )
                    $toDelete->append( $alias );
            }
        }
        return $toDelete;    
    }
    
    //bools
    
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
    
    public function fetchAliases(){
        $this->seo->getAliasesInstance()->get( array( 'pageID'=>$this->getID() ) );
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
    
    //JSON
    public function toJSON(){
        return parent::toJSON() + json_encode($this);
    }
}

?>
