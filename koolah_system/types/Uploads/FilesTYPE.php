<?php
/**
 * FilesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FilesTYPE
 * 
 * Extends Nodes to work with FileTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Uploads
 */
class FilesTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the uploads collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = UPLOADS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * files
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function files(){ return $this->nodes; }
    
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
                $file = new FileTYPE();
                $file->read( $bson );
                $this->append( $file );
            }
        }   
    }
}
