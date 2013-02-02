<?php 

class KoolahUsers extends UsersTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
		if ( $this->sessionUser->isSuper() )
			return parent::get($q, $fields, $orderBy);
		$params = array( 'active'=>1 );
		if ( $q )
			$q = array_merge( $q, $params );
		else 
			$q = $params;
		return parent::get($q, $fields);
	}
}

?>