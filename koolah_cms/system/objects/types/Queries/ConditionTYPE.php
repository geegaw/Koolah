<?php

class ConditionTYPE{
        
    //PUBLIC
    public $andOr = '';
    public $not = '';
    public $field = '';
    public $booleanOperator = '';
    public $fieldExpr = '';
    
    public function mkInput( $page=null, $custom=false ){
        if ( !$page ){
            $page = new PageTYPE();
        }
           
        return $html;
    }
    
    /***
     * MONGO FUNCTIONS
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

    public function read( $bson ){
//debug::printr($bson, true);     
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
    
    public function readAssoc( $bson ){
//debug::printr($bson);
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
    
    public function readObj( $obj ){
        if ( $obj ){
//debug::printr($obj, true);            
            $this->andOr = $obj->andOr;            
            $this->not = $obj->not;
            $this->field = $obj->field;            
            $this->booleanOperator = $obj->booleanOperator;
            $this->fieldExpr = $obj->fieldExpr;           
            
        }        
    }


    /***
     * Helpers
     */
    
}
?>
