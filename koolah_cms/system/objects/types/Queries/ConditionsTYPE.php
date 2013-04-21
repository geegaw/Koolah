<?php

class ConditionsTYPE{
    public $conditions = array();
    
    //GETTERS
    
    /***
     * MONGO FUNCTIONS
     */
    
    public function read( $bson ){
        if ( $bson && isset($bson['conditions']) ){
            $this->conditions = array();         
            foreach ( $bson['conditions'] as $node ){
                $condition = new ConditionsTYPE();
                $condition->read( $node );
                $this->conditions[] = $condition;
            }
        }                       
    }
    
    public function prepare(){
        $bson = null;
        if ( $this->conditions ){
            foreach ( $this->conditions as $condition )
                $bson[] = $condition->prepare();
        }                     
        return $bson;  
    }
    /***/ 
              
    public function isEmpty(){
        return count($this->conditions) === 0;
    }               
} 