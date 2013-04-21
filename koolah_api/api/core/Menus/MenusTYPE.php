<?php

class MenusTYPE extends Nodes
{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = MENU_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function Menus(){ return $this->nodes; }
    
    
    //GETTERS
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $menu = new MenuTYPE();
                $menu->read( $bson );
                $this->append( $menu );
            }
        }   
    }
    
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        return array( 'Menus'=>parent::prepare() );
    }
    
    public function read( $bson ){
        if ( $bson && isset($bson['fields']) ){
            $this->clear();         
            foreach ( $bson['fields'] as $node )
                $this->append($node);
        }                       
    }
    /***/ 
               
} 