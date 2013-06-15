<?php
/**
 * UserPermissionsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserPermissionsTYPE
 * 
 * Extends Nodes to work with UserPermissionTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Users
 */
class UserPermissionsTYPE extends Nodes{
	    
	/**
     * prefix to use
     * @var string
     * @access public
     */
    public $prefix;
	
    /**
     * constructor
     * @param customMongo $db
     * @param string $prefix     
     */    
    public function __construct( $db=null, $prefix='permissions'  ){
    	parent::__construct( $db, USER_COLLECTION );
		$this->prefix = $prefix;	
    }
    
    /**
     * pages
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function permissions(){ return $this->nodes; }
	
	/**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
		$bsonArray = parent::get( $q, $fields, $orderBy);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$permission = new UserPermissionTYPE( $this->db, $this->collection );
				$permission->read( $bson );
				$this->append( $permission );
			}
		}	
	}
	
     /**
     * hasPermission
     * check if user has permission
     * @access public   
     * @param PermissionTYPE $suspect
     * @return bool     
     */    
    public function hasPermission( $suspect ){
		if ( $this->permissions() ){
			foreach ( $permissions as $permission ){
				if ( $suspect == $permission->name )
					return true;
			}
		}
		return false;
	} 
	 
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		$permissions = null;
		if ($this->permissions()){
			foreach( $this->permissions() as $permission ){
				$permissions[] = $permission;
			}
		}
		return array( $this->prefix=>$permissions );
	}
	
	/**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		if ( $bson && isset($bson[$this->prefix]) && count($bson[$this->prefix])){
			$this->clear();			
			foreach ( $bson[$this->prefix] as $permission ){
				$this->append( $permission );
			}				
		}						
	}
} 
?>