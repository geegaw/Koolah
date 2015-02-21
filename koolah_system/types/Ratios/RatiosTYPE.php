<?php
/**
 * RatiosTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatiosTYPE
 * 
 * Extends Nodes to work with RatioTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Ratios
 */
class RatiosTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the ratios collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = RATIOS_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * ratios
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function ratios(){ return $this->nodes; }
    
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $offset=0, $limit=null, $distinct=null  ){
    	if (!$orderBy)
			$orderBy=array('label'=>1);
        $bsonArray = parent::get( $q, $fields , $orderBy, $offset, $limit, $distinct);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $ratio = new RatioTYPE();
                $ratio->read( $bson );
                $this->append( $ratio );
            }
        }   
    }
}
