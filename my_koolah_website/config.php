<?php
/**
 * config
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * config
 * 
 * class that initilizes db connection
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms
 */ 
class config{
    
    /**
     * connection to db
     * @var customMongo
     * @access  public
     */
    public $cmsMongo;
    
    /**
     * constructor
     * sets db connection
    */    
    public function __construct(){
        $this->getDBC();
    }
    
    /**
     * getDBC
     * gets and sets connection to db
     * @access  private
    */    
    private function getDBC(){
        $this->cmsMongo = new customMongo('cms');    
    }
}
?>