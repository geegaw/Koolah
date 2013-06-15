<?php
/**
 * ImagesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ImagesTYPE
 * 
 * Extends Nodes to work with ImageTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Uploads
 */
class ImagesTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the images collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = IMAGES_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * images
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function images(){ return $this->nodes; }
    
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null  ){
        $bsonArray = parent::get( $q, $fields , $orderBy);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $image = new ImageTYPE();
                $image->read( $bson );
                $this->append( $image );
            }
        }   
    }
}
