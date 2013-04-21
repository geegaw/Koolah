<?php

class QueryTYPE{
        
    //PUBLIC
    public $query = '';
    public $type = '';
    public $templateID = '';
    public $conditions;
    
    public $className = 'pages';
     
    private $q;
    
    //CONSTRUCT
    public function __construct(){
        $this->conditions = new ConditionsTYPE();
    }
    
    
    private function build(){
        $this->q = array( 'templateID' => $this->templateID);
    }
    
    public function execute(){
        if ( !$this->q )
            $this->build();
        
        $objs = null;
        
        $class = ucfirst($this->className).'TYPE';        
        if ( class_exists($class)){
            $objs = new $class();
            if (method_exists($objs, 'get'))    
                $objs->get( $this->q );
        }    
        if ( is_object( $objs ) && property_exists($objs, 'nodes') )
            return $objs->pages();
        return $objs;
    }
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array(
            'query'=>$this->query,
            'type'=>$this->type,
            'templateID'=>$this->templateID,
            'className'=>$this->className,
            'conditions'=>$this->conditions->prepare(),
        );
        return $bson;        
    }

    public function read( $bson ){
//debug::printr($bson, true);     
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
        if ( isset($bson['query']) )
            $this->query = $bson['query'];
        if ( isset($bson['type']) )
            $this->type = $bson['type'];
        if ( isset($bson['templateID']) )
            $this->templateID = $bson['templateID'];
        if ( isset($bson['className']) )
            $this->className = $bson['className'];
        
        $this->conditions->read($bson);          
    }
    
    public function readObj( $obj ){
        if ( $obj ){
//debug::printr($obj, true);            
            $this->query = $obj->query;
            $this->type = $obj->type;
            $this->templateID = $obj->templateID;            
            $this->className = $obj->className;            
            $this->conditions->read( $obj->conditions );
        }        
    }
    
    /***
     * Helpers
     */
    
}
?>

