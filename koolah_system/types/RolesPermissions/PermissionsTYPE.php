<?php
/**
 * PermissionsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PermissionsTYPE
 * 
 * Extends Nodes to work with PermissionTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\RolesPermissions
 */
class PermissionsTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the user collection     
     * @param customMongo $db
     */    
    public function __construct( $db=null  ){
    	parent::__construct( $db, USER_COLLECTION );	
    }
    
	/**
     * loadPermissions
     * loads permssions from the config
     * @access  public
     */
    public function loadPermissions(){
		include( CONF.'/permissions.php' );
	}
	
    /**
     * permissions
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
		$bsonArray = parent::get( $q, $fields);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$permission = new PermissionTYPE( $this->db, $this->collection );
				$permission->read( $bson );
				$this->append( $permission );
			}
		}	
	}
	
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		return array( 'permissions'=>parent::prepare() );
	}
	
	/**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
		if ( $bson && isset($bson['permissions']) ){
			$this->clear();			
			foreach ( $bson['permissions'] as $node ){
				$permission = new PermissionTYPE( $this->db, $this->collection );
				$permission->read( $bson );
				$this->append( $permission );
			}				
		}						
	}
	
    
    /**
     * sortByCat
     * sorts permssions by their category
     * @access  public
     * @return assocArray
     */
    public function sortByCat(){
        if ($this->length){
            $tmp = null;
            foreach ( $this->permissions as $perm){
            	$parts = explode( '_', $perm->getLabel() );
				foreach ( $parts as $level ){
					
				}	
            	$tmp[$perm->category][] = $perm;
            }
                
            return $tmp;
        }
    }
} 
?>