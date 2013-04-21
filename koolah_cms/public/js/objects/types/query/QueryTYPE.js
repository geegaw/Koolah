function QueryTYPE(  ){
    
    this.$msgBlock = $('#msgBlock');
    
    if ( typeof TemplatesTYPE === 'undefined' ){
        alert('Query Type requires Templates. Please contact your administrator');
        return;
    }
    
    if (typeof templates === 'undefined' ){
        var templates = new TemplatesTYPE();
         if ( templates.empty() ){
            templates.get(null, null, this.$msgBlock, false);
        }
    }
    templates.sort();
        
    this.query = '';
    
    this.type = '';
    this.templateID = '';
    this.conditions = new ConditionsTYPE();
    
        
    var self = this;
    
    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.type = data.type;
        self.templateID = data.templateID;
        self.conditions.fromAJAX( data.conditions );
    }

    this.toAJAX = function(){
        var tmp = {};
            tmp.type = self.type;
            tmp.templateID = self.templateID;
            tmp.conditions = self.conditions.toAJAX();            
        return tmp;
    }
    
    this.readForm = function(){
        self.type = $('#queryTemplateType').val();
        self.templateID = $('#queryTemplateId').val();  
        $('#queryConditionals .queryCondition:visible').each(function(){
                var condition = new ConditionTYPE();
                condition.readForm( $(this) );
                self.conditions.append( condition );
        })
        
        console.log( self );                  
    }
    
    this.fillForm = function(){
        $('#queryTemplateType').val( self.type );
        self.resetTemplatesDropdown();
        self.fillTemplatesDropdown( self.type );
        $('#queryTemplateId').val( self.templateID );
        $('#queryTemplateId').show();
        
        if ( self.conditions.count() ){
            for (var i=0; i < self.conditions.count(); i++ ){
                console.log(i)
                self.showConditionForm();
                
                var $queryCondition = $('#queryConditionals .queryCondition:last');
//                self.resetFieldDropdown( $queryCondition );
//                self.fillFieldDropdown( $queryCondition );
                
                var condition = self.conditions.els()[i];
                condition.fillForm( $queryCondition );
            }
        }  
    }
    
    this.resetTemplatesDropdown = function(){
        var $noSelection = $('#queryTemplateId option:first');
        $('#queryTemplateId').html( $noSelection );
    }
    
    this.fillTemplatesDropdown = function(type){
        var typeTemplates = templates[type];
        if (templates){
            for( var i =0; i < typeTemplates.length; i++ ){
                var template = typeTemplates[i];
                $('#queryTemplateId').append( '<option value="'+template.parent.id+'">'+template.label.label+'</option>' );
            }
        }
    }
    
    this.showConditionForm = function(){
        $('#queryCondition').clone().appendTo( '#queryConditionals' );
        var $queryCondition = $('#queryConditionals .queryCondition:last');
        self.fillFieldDropdown($queryCondition);
        if ( $('#queryConditionals .queryCondition').length > 1)
            $('#queryConditionals .queryCondition:last .queryBoolean').show();
        $('#queryConditionals .queryCondition:last').removeAttr('id').show();
    }
    
    this.resetFieldDropdown = function($queryCondition){
        var $noSelection = $queryCondition.find('.queryTemplateFields option:first');
        $queryCondition.find('.queryTemplateFields').html( $noSelection );
    }
    
    this.fillFieldDropdown = function($queryCondition){
        var templateID = $('#queryTemplateId').val();
        var template = templates.find( templateID );
        var fields = template.getAllFields();
        
        var $selection = $queryCondition.find('.queryTemplateFields');
        if ( fields ){
            for (var i=0; i < fields.length; i++){
                var field = fields[i];
                $selection.append( '<option value="'+field.label.ref+'">'+field.label.label+'</option>' );
            }
        }
    }
    
    
    
    $('#queryTemplateType').change(function(){
        var $this = $(this);
        if ( $this.val() != 'no_selection' ){
            self.resetTemplatesDropdown();
            self.fillTemplatesDropdown( $this.val() );
            $('#queryTemplateId').show();
        }
    })
    
    $('#queryTemplateId').change(function(){
        var $this = $(this);
        if ($this.val() == 'no_selection')
            $('#addCondition').hide();
        else
            $('#addCondition').show();
    })
    
    $('#addCondition').click(function(){
        self.showConditionForm();
    })
    
    $('body').on('change', '.queryTemplateFieldComparisonOperator', function(){
        var $this = $(this);
        var $parent = $this.parents('.queryCondition:first');
        if ($this.val() === 'empt')
            $parent.find('.queryTemplateFieldExpr').hide();
        else
            $parent.find('.queryTemplateFieldExpr').show();
    })
    
    $('body').on('click', '.queryCondition .remove', function(){
        var $this = $(this);
        var $parent = $this.parents('.queryCondition:first');
        $parent.remove();
        $('#queryConditionals .queryCondition:first .queryBoolean').hide();
    })
}
