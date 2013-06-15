<?php
/**
 * StatusTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * StatusTYPE
 * 
 * a better option to return then a bool
 * with status type you can add a more descriptive message
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */ 
class StatusTYPE{
    /**
     * status boolean
     * @var bool
     * @access  public
     */
    public $status; 
    
    /**
     * message
     * @var string
     * @access  public
     */
    public $msg;
    
    
    /**
     * constructor
     * defaults to a success
     * @access  public
     * @param string $msg 
     * @param bool $status
     */    
    public function __construct( $msg = "success", $status = true ){
          $this->status = $status;
          $this->msg = $msg;
    }
    
    /**
     * success
     * returns true is status is true
     * @access  public
     * @return bool
     */    
    public function success(){ return ($this->status === true); }
    
    /**
     * setFalse
     * sets to false and can pass a message
     * @access  public
     * @param string $msg
     */    
    public function setFalse($msg='error'){ $this->setStatus( false, $msg ); }
    
    /**
     * setTrue
     * sets to True and can pass a message
     * @access  public
     * @param string $msg
     */    
    public function setTrue($msg='success'){ $this->setStatus( true, $msg ); }    
    
    /**
     * setStatus
     * set both status and message
     * @access  private
     * @param bool $status
     * @param string $msg
     */    
    private function setStatus( $status, $msg ){
        $this->status = $status;
        $this->msg = $msg;
    }
}

?>
