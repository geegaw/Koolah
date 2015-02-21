<?php
/**
 * FieldsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FieldsTYPE
 * 
 * Extends Nodes to work with FieldTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class FieldsTYPE extends Nodes{
    
    /**
     * constructor
     * initiates db to the fields collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = FIELD_COLLECTION ){
		parent::__construct( $db, $collection );
    }
	
	/**
     * fields
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function fields(){ return $this->nodes; }
	
    
    /**
     * mkInput
     * make input for fields
     * @access  public
     * @param PageTYPE $page
     * @return string
     */
    public function mkInput($page){
        $html = '';
        if ( $this->length() ){
            foreach( $this->fields() as $field )
                $html.= $field->mkInput( $page );
        }
        return $html;
    }
    
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		return array( 'fields'=>parent::prepare() );
	}
    
    /**
     * export
     * prepares for sending to another user
     * @access  public
     * @return assocArray
     */
    public function export(){
        return array( 'fields'=>parent::export() ); 
    }
    
    /**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
        $this->clear();        
        if ( $bson ){
            if (is_string($bson)){
                $bson = json_decode($bson);
                return self::read($bson);
            }
            elseif ( is_array($bson) && array_key_exists('fields', $bson) ){
            	$fields = $bson['fields'];
				if (!$fields)
					$fields = array();
			}
            elseif( is_object($bson) )
                $fields = $bson->fields;    
            else 
                $fields = $bson;

            foreach ( $fields as $field_bson ){
                $field = new FieldTYPE( $this->db );
                $field->read( $field_bson );
                $this->append( $field );    
            }                 
        }
    }	   
	
}

 