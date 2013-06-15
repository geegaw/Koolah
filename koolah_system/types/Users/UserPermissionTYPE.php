<?php
/**
 * UserPermissionTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserPermissionTYPE
 * 
 * Users permission
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Users
 */
class UserPermissionTYPE extends Node{
  	
	/**
     * permission name
     * @var string
     * @access public
     */
    public $name;
	
	/**
     * constructor
     * initiates db to the users collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		parent::__construct( $db, USER_COLLECTION );
		$this->name = '';
	}

	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		$bson = array(
			'name' => $this->name,
		);
		return parent::prepare() + $bson;		
	}
	
	/**
     * read
     * reads from db - clears and handles children's reading
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		parent::read($bson);
		if ( isset($bson['name']) )
			$this->name = $bson['name'];
	}
}
?>
