<?php
/**
 * UserActionTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserActionTYPE
 * 
 * information about action user performed on a page
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\UserHistory
 */
class UserActionTYPE{
        
    /**
     * name of class
     * @var string
     * @access public
     */
    public $className = '';
    
    /**
     * object id
     * @var string
     * @access public
     */
    public $id = '';  
    
    /**
     * name of action
     * @var string
     * @access public
     */
    public $action = '';
    
    /**
     * time action was performed
     * @var date
     * @access private
     */
    private $timestamp;  
    
    
    /**
     * constructor
     */ 
     public function __construct(){
        $this->timestamp = date(TIMESTAMP_FORMAT);
    }
    
    /**
     * set
     * easy one call to see all params
     * @access public   
     * @param string $action
     * @param string $className
     * @param string $id
     */    
    public function set( $action, $className, $id ){
        $this->action = $action;
        $this->className = $className; 
        $this->id = $id;
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @param bool $forSave
     * @return assocArray
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
        if (isset($bson['className']))
            $this->className = $bson['className'];
        if (isset($bson['id']))
            $this->id = $bson['id'];
        if (isset($bson['timestamp']))
            $this->timestamp = $bson['timestamp'];
        if (isset($bson['action']))
            $this->action = $bson['action'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->className = $obj->className;
            $this->id = $obj->id;
            $this->timestamp = $obj->timestamp;
            $this->action = $obj->action;
        }    
    }  
}