<?php
class UserActionTYPE{
    //PUBLIC
    public $className = '';
    public $id = '';  
    public $action = '';
    
    //PRIVATE
    private $timestamp;  
    
    
    //CONSTRUCT
    public function __construct( $db=null ){
        $this->timestamp = date(TIMESTAMP_FORMAT);
    }
    
    public function set( $action, $className, $id ){
        $this->action = $action;
        $this->className = $className; 
        $this->id = $id;
    }
    
    //fetchers
    //Bools
    //Getters
    /***
     * MONGO FUNCTIONS
     */
    public function prepare($forSave=false){
        $bson = array( 
           'className'=>$this->className,
           'id'=>$this->id,
           'timestamp'=>$this->timestamp,
           'action' => $this->action,
         );
         return $bson;
    }
    
    public function read( $bson ){
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
        if (isset($bson['className']))
            $this->className = $bson['className'];
        if (isset($bson['id']))
            $this->id = $bson['id'];
        if (isset($bson['timestamp']))
            $this->timestamp = $bson['timestamp'];
        if (isset($bson['action']))
            $this->action = $bson['action'];
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            $this->className = $obj->className;
            $this->id = $obj->id;
            $this->timestamp = $obj->timestamp;
            $this->action = $obj->action;
        }    
    }  
}