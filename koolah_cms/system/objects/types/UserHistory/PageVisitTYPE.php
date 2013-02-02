<?php
class PageVisitTYPE extends Node{
    //PUBLIC
    public $title = '';
    public $url = '';  
    
    //PRIVATE
    private $userID;
    private $timestamp;  
    private $actions;
    
    //CONSTRUCT
    public function __construct( $userID=null, $db=null ){
        parent::__construct( $db, USER_HISTORY_COLLECTION );
        
        $this->userID = $userID;    
        $this->timestamp = date(TIMESTAMP_FORMAT);
        $this->actions = new UserActionsTYPE();
    }
    
    
    public function update( $title, $url){
        $this->title = $title;
        $this->url = $url;
    }
    
    public function updateAction( $action, $classname, $id ){
        $this->actions->update( $action, $classname, $id );
    }
    
    //Getters
    /***
     * MONGO FUNCTIONS
     */
    public function prepare($forSave=false){
        $bson = array( 
           'title'=>$this->title,
           'url'=>$this->url,
           'timestamp'=>$this->timestamp,
           'actions' => $this->actions->prepare(),
           'userID' => $this->userID,
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
//debug::printr($bson, true);        
        if (isset($bson['title']))
            $this->title = $bson['title'];
        if (isset($bson['url']))
            $this->url = $bson['url'];
        if (isset($bson['timestamp']))
            $this->timestamp = $bson['timestamp'];
        if (isset($bson['actions']))
            $this->actions->read( $bson['actions'] );
        if (isset($bson['userID']))
            $this->userID = $bson['userID'];
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            $this->title = $obj->title;
            $this->url = $obj->url;
            $this->timestamp = $obj->timestamp;
            $this->actions->read( $obj->actions );
            $this->userID = $obj->userID;
            
        }    
    }  
}