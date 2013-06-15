<?php
/**
 * UserRolesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserRolesTYPE
 * 
 * Extends RolesTYPE to connect with UserTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Users
 */
class UserRolesTYPE extends RolesTYPE{
	    
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
    public function __construct( $db=null, $prefix = 'roles' ){
		parent::__construct($db);
		$this->prefix = $prefix;
	}
	
	
	/**
     * hasSuper
     * check if one of the roles is superuser
     * @return bool
     */    
    public function hasSuper(){
		if ( $this->roles() ){
			foreach( $this->roles() as $role ){
				if( $role->isSuper() )
					return true;
			}
		}
        return false;
	}
	
	/**
     * hasAdmin
     * check if one of the roles is an admin
     * can filter that user is admin and not superuser
     * @param bool $filterSupers
     * @return bool
     */    
    public function hasAdmin( $filterSupers=false ){
		if ( $this->roles() ){
			foreach( $this->roles() as $role ){
				if( $role->isAdmin() && !($filterSupers && $role->isSuper()) )
					return true;
			}
		}
        return false;
	}
	
    /**
     * mkSuper
     * add a super user role and 
     * save if desired 
     * @param bool $save
     * @return StatusTYPE|void
     */    
    public function mkSuper($save=false){
		$super = new RoleTYPE( $this->db );
		$super->mkSuper();
		$this->append( $super );
		
		if ( $save )
		 	return $this->save();
	}
	
	/**
     * mkAdmin
     * add an admin user role and 
     * save if desired 
     * @param bool $save
     * @return StatusTYPE|void
     */    
    public function mkAdmin($save=false){
		$admin = new RoleTYPE( $this->db );
		$admin->mkAdmin();
		$this->append( $admin );

		if ( $save )
		 	return $this->save();
	}
	 
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		$roles = null;	
		if ($this->roles() ){
			foreach ( $this->roles() as $role )
				$roles[]= $role->getID();
		}
		return array( $this->prefix=>$roles );
	}
	
	/**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		if ( $bson && isset($bson[$this->prefix]) ){
			$this->clear();			
			foreach ( $bson[$this->prefix] as $nodeID ){
				$role = new RoleTYPE( $this->db, $this->collection );
				if ( $nodeID === RoleTYPE::SUPER_USER )
					$role->mkSuper();
				elseif ( $nodeID === RoleTYPE::ADMIN )
					$role->mkAmin();
				else	
					$role->getByID( $nodeID );
				$this->append( $role );
			}				
		}	
	}
}