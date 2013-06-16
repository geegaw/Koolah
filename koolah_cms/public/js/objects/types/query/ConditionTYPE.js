/**
 * @fileOverview defines ConditionTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ConditionTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\query
 * @class - a query condition
 * @constructor
 */
function ConditionTYPE(){
    
    /**
     * andOr - AND or OR 
     * @type string
     * @default ''
     */
    this.andOr = '';
    
    /**
     * not - NOT 
     * @type string
     * @default ''
     */
    this.not = '';
    
    /**
     * field - field to question 
     * @type string
     * @default ''
     */
    this.field = '';
    
    /**
     * booleanOperator - ><=, etc 
     * @type string
     * @default ''
     */
    this.booleanOperator = '';
    
    /**
     * fieldExpr - condition to match 
     * @type string
     * @default ''
     */
    this.fieldExpr = '';
        
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'ConditionTYPE'; }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( data ){
        self.andOr = data.andOr;
        self.not = data.not;
        self.field = data.field;
        self.booleanOperator = data.booleanOperator;
        self.fieldExpr = data.fieldExpr;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {};
            tmp.andOr = self.andOr;
            tmp.not = self.not;
            tmp.field = self.field;
            tmp.booleanOperator = self.booleanOperator;
            tmp.fieldExpr = self.fieldExpr;                        
        return tmp;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $el - element to read from 
     */
    this.readForm = function($el){
        if ( $el.find('.queryBoolean').is(':visible') )
            self.andOr = $el.find('.queryBooleanFields').val();
        self.not = $el.find('.not').is(':checked') ? 'not' : '';
        self.field = $el.find('.queryTemplateFields').val();
        self.booleanOperator = $el.find('.queryTemplateFieldComparisonOperator').val();
        self.fieldExpr = $el.find('.queryTemplateFieldExpr ').val();        
    }
    
    /**
     * fillForm
     * - fill in a form 
     * @param jQuery dom obj $el - element to fill
     */
    this.fillForm = function($el){
        if ( self.andOr )
            $el.find('.queryBoolean').val( self.andOr );            
        if (self.not == 'not')
            $el.find('.not').attr('checked', 'checked');
        $el.find('.queryTemplateFields').val( self.field );
        $el.find('.queryTemplateFieldComparisonOperator').val( self.booleanOperator );
        $el.find('.queryTemplateFieldExpr ').val( self.fieldExpr );        
    }
}