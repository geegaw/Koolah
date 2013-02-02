<?php

class KoolahFile extends FileTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_files') )
		|| ( !$this->id && $this->sessionUser->can('c_files') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
    
    public function upload($bson=null){
        if ($this->id && $this->sessionUser->can('c_files') )
            return parent::upload($bson);
        return cmsToolKit::permissionDenied();
    }
		
	public function del(){
		if ( $this->sessionUser->can('d_files') ) 
		  return parent::del();
		return cmsToolKit::permissionDenied();	
	}
	
}

?>