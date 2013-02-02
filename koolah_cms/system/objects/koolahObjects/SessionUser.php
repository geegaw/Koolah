<?php

class SessionUser extends UserTYPE{
	
	public $status;
	
	public function __construct( $db=null ){
	    parent::__construct($db);    
	    $this->status = new StatusTYPE();	
		if (!session_id())
			session_start();
        if( isset($_SESSION['user']) && $_SESSION['user'] )
		    $this->getFromSession();
		else
		    $this->status->setFalse('Permission Denied: User not signed in');
    }
	
    public function signin($username){
        if( $this->getByUsername($username) ){
            $this->status = new StatusTYPE();
            if (!session_id())
                session_start();
            $_SESSION['user'] = $this; 
        }
    }
    
    public function signout(){
        $_SESSION['user'] = null;
        session_unset();
    }
    
    private function getFromSession(){
        $user = unserialize(serialize($_SESSION['user']));
        $bson = $user->prepare();
        $this->read( $bson );
    }
}

?>