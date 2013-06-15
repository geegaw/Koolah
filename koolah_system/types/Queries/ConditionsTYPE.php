<?php
/**
 * ConditionsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ConditionTYPE
 * 
 * Object to manipulate multiple ConditionTYPEs 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Queries
 */
class ConditionsTYPE{
        
    /**
     * array of condition
     * @var array
     * @access public
     */
    public $conditions = array();
    
    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
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
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = null;
        if ( $this->conditions ){
            foreach ( $this->conditions as $condition )
                $bson[] = $condition->prepare();
        }                     
        return $bson;  
    }

    /**
     * isEmpty
     * returns true if no conditions
     * @access  public
     * @return bool
     */
    public function isEmpty(){
        return count($this->conditions) === 0;
    }               
} 