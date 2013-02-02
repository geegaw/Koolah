<?php 

class KoolahAliases extends AliasesTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
    
    
    public function save($bson=null){
        return cmsToolKit::permissionDenied();
    }
        
    public function del(){
        return cmsToolKit::permissionDenied();  
    }	
}

?>