<?php

class KoolahTemplate extends TemplateTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_templates') )
		|| ( !$this->id && $this->sessionUser->can('c_templates') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
		
	public function del(){
		if ( $this->sessionUser->can('d_templates') ) 
		  return parent::del();
		return cmsToolKit::permissionDenied();	
	}
	
}

?>