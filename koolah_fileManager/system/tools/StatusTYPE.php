<?php


class StatusTYPE
{
    public $status; 
    public $msg;
    
    
    public function __construct( $msg = "success", $status = true )
    {
          $this->status = $status;
          $this->msg = $msg;
    }
    
    public function success(){ return ($this->status === true); }
    
    public function setFalse($msg='error'){ $this->setStatus( false, $msg ); }
    public function setTrue($msg='success'){ $this->setStatus( true, $msg ); }    
    private function setStatus( $status, $msg )
    {
        $this->status = $status;
        $this->msg = $msg;
    }
	
	
}

?>
