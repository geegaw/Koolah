<?php
/**
 * UserActionsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserActionsTYPE
 * 
 * Extends Nodes to work with UserActionTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\UserHistory
 */
class UserActionsTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the pages collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = null ){
        parent::__construct( null, null );    
    }
    
    /**
     * userActions
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function userActions(){ return $this->nodes; }
    
    /**
     * get
     * disable get
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=null, $offset=0, $limit=null, $distinct=null  ){}
    
    /**
     * get
     * disable save
     * @access public          
     * @param assocArray $bson
     */    
    public function save( $bson=null ){}
    
    /**
     * get
     * disable del
     * @access public          
     */    
    public function del(){}
    
    /**
     * update
     * add latest user action
     * @access public          
     * @param string $action
     * @param string $classname
     * @param string $id
     */    
    public function update( $action, $classname, $id ){
        if ( $action && $classname && $id ){
            $latestAction = new UserActionTYPE();
            $latestAction->set( $action, $classname, $id );
            $this->append( $latestAction );
        }
    }
}
