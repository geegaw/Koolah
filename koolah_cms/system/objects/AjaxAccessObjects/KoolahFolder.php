<?php

class KoolahFolder extends FolderTYPE{
	
	private $sessionUser;
	public $status;
	
	public function  __construct($db=null){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )
			parent::__construct( $db );
	}
	
	public function save($bson=null){
		if ( ($this->id && $this->sessionUser->can('m_folders') )
		|| ( !$this->id && $this->sessionUser->can('c_folders') )){
			return parent::save($bson);
		}
		return cmsToolKit::permissionDenied();
	}
    
    /*
    public function getByID($id=null){ return $this->getOne(); }
    public function get($q=null, $fields=null){ return $this->getOne(); }
    public function getOne(){
        if ( ($this->id && ($this->sessionUser->can('m_folders')  || $this->sessionUser->can('d_folders')))
             || ( !$this->id && $this->sessionUser->can('c_folders') )){
            parent::getRoot();
            return $this;
        }
        return cmsToolKit::permissionDenied();
    }
	*/
		
	public function del(){
		if ( $this->sessionUser->can('d_folders') ) 
		  return parent::del();
		return cmsToolKit::permissionDenied();	
	}
	
}

?>