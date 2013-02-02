<?php

class config{
    
    public $cmsMongo;
    
    public function __construct(){
        $this->getDBC();
    }
    
    private function getDBC(){
        $this->cmsMongo = new customMongo('cms');    
    }
    
}

?>