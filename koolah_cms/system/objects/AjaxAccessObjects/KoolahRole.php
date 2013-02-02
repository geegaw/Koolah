<?php

class KoolahRole extends RoleTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_roles') )
		|| ( !$this->id && $this->sessionUser->can('c_roles') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
		
	public function del(){
		if ( $this->sessionUser->can('d_roles') ){
			if ( $this->sessionUser->isSuper() && $this->isInactive() )
				return parent::destroy();
			return parent::delete();
		}
		return cmsToolKit::permissionDenied();	
	}
	
}

?>