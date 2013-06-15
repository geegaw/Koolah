<?php
/**
 * RoleTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RoleTYPE
 * 
 * Class to work with a role
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\RolesPermissions
 */
class RoleTYPE extends Node{
  	
    const SUPER_USER = 'superuser';
	const ADMIN = 'admin';
	
	/**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * array of permissions
     * @var array
     * @access public
     */
    public $permissions;
	
    /**
     * constructor
     * initiates db to the menu collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		parent::__construct( $db, ROLES_COLLECTION );
		$this->label = new LabelTYPE( $db, ROLES_COLLECTION );
		$this->permissions = null;
	}
    
	/**
     * isSuper
     * is a super user role
     * @access public   
     * @return bool     
     */    
    public function isSuper(){ return $this->label->getRef() === RoleTYPE::SUPER_USER; }
	
	/**
     * isAdmin
     * is an admin user role
     * @access public   
     * @return bool     
     */    
    public function isAdmin(){ return ($this->isSuper() || $this->label->getRef() === RoleTYPE::ADMIN); }
	
	/**
     * can
     * checks if a role has desired permission
     * @access public   
     * @param string $permission
     * @return bool     
     */    
    public function can($permission){ return ($this->isAdmin() || ($this->permissions && in_array($permission, $this->permissions))); } 
	
	
	
	/**
     * mkSuper
     * make a role a superuser role
     * @access public   
     * @param bool $save
     * @return StatusTYPE     
     */    
    public function mkSuper( $save=false ){
		 $this->id = RoleTYPE::SUPER_USER;	
		 $this->label->label = RoleTYPE::SUPER_USER;
		 $this->label->setRef( RoleTYPE::SUPER_USER );
		 if ( $save )
		 	return $this->save();
	}
	
	/**
     * mkAmin
     * make a role an admin role
     * @access public   
     * @param bool $save
     * @return StatusTYPE     
     */    
    public function mkAmin( $save=false ){
		 $this->id = RoleTYPE::ADMIN;	
		 $this->label->label = RoleTYPE::ADMIN;
		 $this->label->setRef( RoleTYPE::ADMIN );
		 if ( $save )
		 	return $this->save();
	}
	
	/**
     * clear
     * empty all permissions in a role
     * @access public   
     * @param bool $save
     * @return StatusTYPE     
     */    
    public function clear( $save=false ){
        $this->label->clear();
        $this->permissions->clear();
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
		if ( !$this->id )
			$this->label->setRef();	
		$bson['permissions'] = $this->permissions;
		return parent::prepare() + $bson + $this->label->prepare();		
	}
	
	/**
     * read
     * reads from db - clears and handles children's reading
     * @access  public
     * @param assocArray
     */
    public function read( $bson ){
		parent::read($bson);
		$this->label->read( $bson );
		if ( $bson['permissions'] )
			$this->permissions = $bson['permissions'];
	}
}


?>
