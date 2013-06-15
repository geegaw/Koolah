<?php
/**
 * ConditionTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ConditionTYPE
 * 
 * Creates an actual query
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Queries
 */
class ConditionTYPE{
        
    /**
     * and or or condition
     * @var string
     * @access public
     */
    public $andOr = '';
    
    /**
     * not
     * @var string
     * @access public
     */
    public $not = '';
    
    /**
     * field to query
     * @var string
     * @access public
     */
    public $field = '';
    
    /**
     * operator suchas =, !=, >, etc
     * @var string
     * @access public
     */
    public $booleanOperator = '';
    
    /**
     * condition to match against
     * @var string
     * @access public
     */
    public $fieldExpr = '';
    
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array(
            'andOr'=>$this->andOr,
            'not'=>$this->not,
            'field'=>$this->field,
            'booleanOperator'=>$this->booleanOperator,
            'fieldExpr'=>$this->fieldExpr,
        );
        return $bson;        
    }

    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
        parent::read($bson);
        if ( is_array($bson) )
            self::readAssoc($bson);
        elseif( is_object($bson) )
            self::readObj( $bson );
        elseif( is_string($bson) )
            $this->readJSON( $bson );
        else 
            // TODO return error
            return;  
    }
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if ( isset($bson['andOr']) )
            $this->andOr = $bson['andOr'];
        if ( isset($bson['not']) )
            $this->not = $bson['not'];
        if ( isset($bson['field']) )
            $this->field = $bson['field'];
        if ( isset($bson['booleanOperator']) )
            $this->booleanOperator = $bson['booleanOperator'];
        if ( isset($bson['fieldExpr']) )
            $this->fieldExpr = $bson['fieldExpr'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->andOr = $obj->andOr;            
            $this->not = $obj->not;
            $this->field = $obj->field;            
            $this->booleanOperator = $obj->booleanOperator;
            $this->fieldExpr = $obj->fieldExpr;           
            
        }        
    }
}
?>
