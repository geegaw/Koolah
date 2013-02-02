<?php

class KoolahRatio extends RatioTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_ratios') )
		|| ( !$this->id && $this->sessionUser->can('c_ratios') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
		
	public function del(){
		if ( $this->sessionUser->can('d_ratios') ) 
		  return parent::del();
		return cmsToolKit::permissionDenied();	
	}
	
}

?>