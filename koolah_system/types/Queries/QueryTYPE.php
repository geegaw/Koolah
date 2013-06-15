<?php
/**
 * QueryTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * QueryTYPE
 * 
 * builds a query that can then be executed by another object to access
 * other data elements dynamically
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Queries
 */
class QueryTYPE{
        
    /**
     * string of query
     * @var string
     * @access public
     */
    public $query = '';
    
    /**
     * type of query
     * @var string
     * @access public
     */
    public $type = '';
    
    /**
     * ref to template
     * @var string
     * @access public
     */
    public $templateID = '';
    
    /**
     * array of conditions
     * @var ConditionsTYPE
     * @access public
     */
    public $conditions;
    
    /**
     * class to query against
     * @var string
     * @access public
     */
    public $className = 'pages';
     
    /**
     * mongo query
     * @var assocArray
     * @access private
     */
    private $q;
    
    /**
     * constructor
     */    
    public function __construct(){
        $this->conditions = new ConditionsTYPE();
    }
    
    
    /**
     * build
     * intiate the beginning of a query
     * @access private     
     */    
    private function build(){
        $this->q = array( 'templateID' => $this->templateID);
    }
    
    /**
     * execute
     * execute a query
     * requires string class naming convention of ClassnameTYPE
     * @access public     
     * @return array
     */    
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
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
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

    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
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
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->query = $obj->query;
            $this->type = $obj->type;
            $this->templateID = $obj->templateID;            
            $this->className = $obj->className;            
            $this->conditions->read( $obj->conditions );
        }        
    }
}
?>

