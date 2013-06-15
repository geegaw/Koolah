<?php
/**
 * RatioSizesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatioSizesTYPE
 * 
 * class to work with RatioSizeTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Ratios
 */
class RatioSizesTYPE{
        
    /**
     * array of sizes
     * @var array
     * @access public
     */
    public $sizes = array();    
        
    /**
     * clear
     * empties array
     * @access public     
     * @return array     
     */    
    public function clear(){ $this->sizes = array(); }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = null;    
        if ( $this->sizes ){
            foreach ( $this->sizes as $size ){
                $bson[] = $size->prepare();
            }
        }   
        return $bson;
    }
    
    
    /**
     * read
     * reads from db - clears and handles children's reading
     * @access  public
     * @param assocArray
     */
    public function read( $bsonArray ){
        $this->clear();
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $ratio = new RatioSizeTYPE();
                $ratio->read( $bson );
                $this->sizes[] = $ratio;
            }
        }   
    }
}
