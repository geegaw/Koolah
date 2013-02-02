<?php

class KoolahImage extends ImageTYPE{
    
    private $sessionUser;
    public $status;
    
    public function  __construct($db){
        $this->sessionUser = new SessionUser( $db );
        $this->status = $this->sessionUser->status;
        if ( $this->status->success() )
            parent::__construct( $db );
    }
    
    public function save($bson=null){
        if ( $this->sessionUser->can('crop_files') )
            return parent::save($bson);
        return cmsToolKit::permissionDenied();
    }
    
    public function del(){
        if ( $this->sessionUser->can('d_files') ) 
          return parent::del();
        return cmsToolKit::permissionDenied();  
    }
    
}

?>