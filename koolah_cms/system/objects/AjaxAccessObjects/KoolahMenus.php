<?php 

class KoolahMenus extends MenusTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db=null){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
}

?>