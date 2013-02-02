<?php

class UserPermissionTYPE extends Node{
  	
	public $name;
	
	//CONSTRUCT
    public function __construct( $db ){
		parent::__construct( $db, USER_COLLECTION );
		$this->name = '';
	}
    
	/***
	 * GETTERS
	 */
    /***/
    
    /***
	 * SETTERS
	 */
    /***/
	
	/***
	 * FETCHERS
	 */
    /***/
	
	
	/***
	 * BOOLS
	 */
	/***/
	
	
	/***
	 * MODIFIERS
	 */
    /***/
	
	
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = array(
			'name' => $this->name,
		);
		return parent::prepare() + $bson;		
	}
	
	public function read( $bson ){
		parent::read($bson);
		if ( isset($bson['name']) )
			$this->name = $bson['name'];
	}
	
	/***
	 * Helpers
	 */
	/***/
}

?>
