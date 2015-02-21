<?php
/**
 * RolesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RolesTYPE
 * 
 * Extends Nodes to work with RoleTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\RolesPermissions
 */
class RolesTYPE extends Nodes{
    
    /**
     * constructor
     * initiates db to the pages collection     
     * @param customMongo $db
     */    
    public function __construct( $db=null  ){
    	parent::__construct( $db, ROLES_COLLECTION );	
    }
    
	/**
     * can
     * checks if a roles has a role with desired permission
     * @access public   
     * @param string $permission
     * @return bool     
     */    
    public function can( $permission ){
		if ( $this->isNotEmpty() ){
			foreach( $this->roles() as $role ){
				if ( $role->can( $permission ) )
					return true;
			}
		}
		return false;
	}
	
    /**
     * roles
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function roles(){ return $this->nodes; }
	
	
	/**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $offset=0, $limit=null, $distinct=null  ){
		$bsonArray = parent::get( $q, $fields,  $orderBy, $offset, $limit, $distinct);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$role = new RoleTYPE( $this->db, $this->collection );
				$role->read( $bson );
				$this->append( $role );
			}
		}	
	}
	
} 
?>
