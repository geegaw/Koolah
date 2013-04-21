function ConditionTYPE(){
    
    this.andOr = '';
    this.not = '';
    this.field = '';
    this.booleanOperator = '';
    this.fieldExpr = '';
        
    var self = this;
    
    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.andOr = data.andOr;
        self.not = data.not;
        self.field = data.field;
        self.booleanOperator = data.booleanOperator;
        self.fieldExpr = data.fieldExpr;
    }

    this.toAJAX = function(){
        var tmp = {};
            tmp.andOr = self.andOr;
            tmp.not = self.not;
            tmp.field = self.field;
            tmp.booleanOperator = self.booleanOperator;
            tmp.fieldExpr = self.fieldExpr;                        
        return tmp;
    }
    
    this.readForm = function($el){
        if ( $el.find('.queryBoolean').is(':visible') )
            self.andOr = $el.find('.queryBooleanFields').val();
        self.not = $el.find('.not').is(':checked') ? 'not' : '';
        self.field = $el.find('.queryTemplateFields').val();
        self.booleanOperator = $el.find('.queryTemplateFieldComparisonOperator').val();
        self.fieldExpr = $el.find('.queryTemplateFieldExpr ').val();        
    }
    
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