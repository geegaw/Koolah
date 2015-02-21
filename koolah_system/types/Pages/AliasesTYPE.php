<?php
/**
 * AliasesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * AliasesTYPE
 * 
 * Extends Nodes to work with AliaseTYPE 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class AliasesTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the aliases collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = ALIAS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * menus
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function aliases(){ return $this->nodes; }
    
    
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=null, $offset=0, $limit=null, $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy, $offset, $limit, $distinct);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $alias = new AliasTYPE( $this->db, $this->collection );
                $alias->read( $bson );
                $this->append( $alias );
            }
        }   
    }
    
    /**
     * mkInput
     * makes the html for a new alias and 
     * renders exisiting aliases
     * @access  public
     * @return string
     */
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
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        return array( 'aliases'=>parent::prepare() );
    }
    
    /**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray|object $bson
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

} 