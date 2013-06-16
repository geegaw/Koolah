/**
 * @fileOverview defines QueryTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * QueryTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\query
 * @class - a user built query
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function QueryTYPE(){
    
   /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $('#msgBlock');
    
    if ( typeof TemplatesTYPE === 'undefined' ){
        alert('Query Type requires Templates. Please contact your administrator');
        return;
    }
    
    if (typeof this.templates === 'undefined' ){
        this.templates = new TemplatesTYPE();
         if ( this.templates.isEmpty() ){
            this.templates.get(null, null, this.$msgBlock, false);
        }
    }
    this.templates.sort();
        
    /**
     * query - fully built query string 
     * @type string
     * @default ''
     */
    this.query = '';
    
    /**
     * type - template type (Page/Widget/Field)
     * @type string
     * @default ''
     */
    this.type = '';
    
    /**
     * templateID - templateID querying from 
     * @type string
     * @default ''
     */
    this.templateID = '';
    
    /**
     * conditions - query conditions 
     * @type ConditionsTYPE
     * @default ''
     */
    this.conditions = new ConditionsTYPE();
        
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'QueryTYPE'; }
    
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( data ){
        self.type = data.type;
        self.templateID = data.templateID;
        self.conditions.fromAJAX( data.conditions );
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {};
            tmp.type = self.type;
            tmp.templateID = self.templateID;
            tmp.conditions = self.conditions.toAJAX();            
        return tmp;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $el - element to read from 
     */
    this.readForm = function(){
        self.type = $('#queryTemplateType').val();
        self.templateID = $('#queryTemplateId').val();  
        $('#queryConditionals .queryCondition:visible').each(function(){
                var condition = new ConditionTYPE();
                condition.readForm( $(this) );
                self.conditions.append( condition );
        })
    }
    
    /**
     * fillForm
     * - fill in a form 
     * @param jQuery dom obj $el - element to fill
     */
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
    
    /**
     * resetTemplatesDropdown
     * - reset dropdown to no value selected 
     * @param jQuery dom obj $el - element to fill
     */
    this.resetTemplatesDropdown = function(){
        var $noSelection = $('#queryTemplateId option:first');
        $('#queryTemplateId').html( $noSelection );
    }
    
    /**
     * fillTemplatesDropdown
     * - fill template dropdown with all appropriate
     * templates 
     * @param jQuery dom obj $el - element to fill
     */
    this.fillTemplatesDropdown = function(type){
        var typeTemplates = self.templates[type];
        if (self.templates){
            for( var i =0; i < typeTemplates.length; i++ ){
                var template = typeTemplates[i];
                $('#queryTemplateId').append( '<option value="'+template.parent.id+'">'+template.label.label+'</option>' );
            }
        }
    }
    
    /**
     * showConditionForm
     * - show a query form 
     * @param jQuery dom obj $el - element to fill
     */
    this.showConditionForm = function(){
        $('#queryCondition').clone().appendTo( '#queryConditionals' );
        var $queryCondition = $('#queryConditionals .queryCondition:last');
        self.fillFieldDropdown($queryCondition);
        if ( $('#queryConditionals .queryCondition').length > 1)
            $('#queryConditionals .queryCondition:last .queryBoolean').show();
        $('#queryConditionals .queryCondition:last').removeAttr('id').show();
    }
    
    /**
     * resetFieldDropdown
     * - reset query dropdown to no selection 
     * @param jQuery dom obj $queryCondition - element to reset
     */
    this.resetFieldDropdown = function($queryCondition){
        var $noSelection = $queryCondition.find('.queryTemplateFields option:first');
        $queryCondition.find('.queryTemplateFields').html( $noSelection );
    }
    
    this.fillFieldDropdown = function($queryCondition){
        var templateID = $('#queryTemplateId').val();
        var template = self.templates.find( templateID );
        var fields = template.getAllFields();
        
        var $selection = $queryCondition.find('.queryTemplateFields');
        if ( fields ){
            for (var i=0; i < fields.length; i++){
                var field = fields[i];
                $selection.append( '<option value="'+field.label.ref+'">'+field.label.label+'</option>' );
            }
        }
    }
    /** dom actions **/
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
