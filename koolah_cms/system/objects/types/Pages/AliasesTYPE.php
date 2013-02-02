<?php

class AliasesTYPE extends Nodes
{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = ALIAS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
    public function aliases(){ return $this->nodes; }
    
    
    //GETTERS
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $alias = new AliasTYPE( $this->db, $this->collection );
                $alias->read( $bson );
                $this->append( $alias );
            }
        }   
    }
    
    function mkInput(){
        $html = '';
        $html.= '<fieldset id="aliasesBody" class="aliases">';
        $html.=     '<fieldset class="aliasesForm">';
        $html.=         '<label for="newAlias">Alias</label>';
        $html.=         '<input type="text" id="newAlias" value="" placeholder="Alias" />';
        $html.=         '<button type="button" id="addNewAlias" class="add">Add</button>';
        $html.=     '</fieldset>';
        $html.=     '<div class="aliasesList fullWidth">';
        if ( $this->length() ){
            foreach( $this->aliases() as $alias )
                $html.= $alias->mkCapsule();
        }
        $html.=     '</div>';
        $html.= '</fieldset>';
        return $html;
    }
    
    /***
     * MONGO FUNCTIONS
     */
     public function save( $bson=null ){
         
     } 
     
    public function prepare(){
        return array( 'aliases'=>parent::prepare() );
    }
    
    /*
    public function prepareIDs(){
        $aliases = null;    
        if ( $this->aliases() ){
            foreach( $this->aliases() as $alias )
                $aliases[] = $alias->getID();
        }
        return array( 'aliases'=>$aliases );
    }
    */
        
    public function read( $bson ){
        if ( $bson ){
            $this->clear();         
            
            $aliases = array();
            if ( is_object($bson) )
                $aliases = $bson->aliases;
            elseif( is_array($bson) )
                $aliases = $bson['aliases'];
            
            foreach ( $aliases as $node ){
                $alias = new AliasTYPE();
                $alias->read( $node );
                $this->append($alias);
            }
        }                       
    }

    /***/ 
               
} 