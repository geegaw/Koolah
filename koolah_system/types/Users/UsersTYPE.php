<?php
/**
 * UsersTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UsersTYPE
 * 
 * Extends Nodes to work with UserTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Users
 */
class UsersTYPE extends Nodes{
    
    /**
     * constructor
     * initiates db to the users collection     
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
    	parent::__construct( $db, USER_COLLECTION );	
    }
    
    /**
     * users
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function users(){ return $this->nodes; }
	
	/**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by lastname asc, firstname 
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('last_name'=>1, 'first_name'=>2), $offset=0, $limit=null, $distinct=null  ){
		$bsonArray = parent::get( $q, $fields, $orderBy, $offset, $limit, $distinct);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$user = new UserTYPE( $this->db, $this->collection );
				$user->read( $bson );
				$this->append( $user );
			}
		}	
	}
	
	/**
     * getSupers
     * gets only superusers
     * @return UsersTYPE
     */    
    public function getSupers(){
		$supers = new UsersTYPE( $this->db );	
		if ( !$this->users() )
			$this->get();
		if ( $this->users() ){			
			foreach( $users as $user){
				if ( $user->isSuper() )
					$supers->append( $user );
			}
		}
		return $supers;
	}
	
	/**
     * getAdmins
     * gets admins, including or excuding super users
     * @param bool $filterSupers
     * @return UsersTYPE
     */    
    public function getAdmins( $filterSupers=false ){
		$admins = null;	
		if ( !$this->users() )
			$this->get();
		if ( $this->users() ){
			$supers = new UsersTYPE( $this->db );
			foreach( $users as $user){
				//is admin && if want to filter supers
				if ( $user->isAdmin() && !($filterSupers && $user->isSuper()) ){
						$admins->append( $user );
				}
			}
		}
		return $admins;
	}
	/***/
	
	
	/**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		if ( $bson && isset($bson['users']) ){
			$this->clear();			
			foreach ( $bson['users'] as $node )
				$this->append($node);
		}						
	}
}  
?>
