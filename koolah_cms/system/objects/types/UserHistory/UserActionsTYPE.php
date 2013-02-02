<?php

class UserActionsTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = null ){
        parent::__construct( null, null );    
    }
    
    //GETTERS
    public function userActions(){ return $this->nodes; }
    
    //Unused parent functions
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){}
    public function save( $bson=null ){}
    public function del(){}
    
    public function update( $action, $classname, $id ){
        if ( $action && $classname && $id ){
            $latestAction = new UserActionTYPE();
            $latestAction->set( $action, $classname, $id );
            $this->append( $latestAction );
        }
    }
}
