$(document).ready(function(){
    var $msgBlock = $('#msgBlock');
    
    if ( typeof TemplatesTYPE === 'undefined' ){
        alert('Query Type requires Templates. Please contact your administrator');
        return;
    }
    
    if (typeof templates === 'undefined' )
        var templates = new TemplatesTYPE();
    
    $('#queryTemplateType').change(function(){
        if ( templates.isEmpty() ){
            templates.get(null, null, $msgBlock, false);
            templates.sort();
        }
        
        var $this = $(this);
        if ( $this.val() != 'no_selection' ){
            resetTemplatesDropdown();
            fillTemplatesDropdown( $this.val() );
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
        showConditionForm();
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
    
    function resetTemplatesDropdown(){
        var $noSelection = $('#queryTemplateId option:first');
        $('#queryTemplateId').html( $noSelection );
    }
    
    function fillTemplatesDropdown(type){
        var typeTemplates = templates[type];
        if (templates){
            for( var i =0; i < typeTemplates.length; i++ ){
                var template = typeTemplates[i];
                $('#queryTemplateId').append( '<option value="'+template.parent.id+'">'+template.label.label+'</option>' );
            }
        }
    }
    
    function showConditionForm(){
        $('#queryCondition').clone().appendTo( '#queryConditionals' );
        var $queryCondition = $('#queryConditionals .queryCondition:last');
        fillFieldDropdown($queryCondition);
        if ( $('#queryConditionals .queryCondition').length > 1)
            $('#queryConditionals .queryCondition:last .queryBoolean').show();
        $('#queryConditionals .queryCondition:last').removeAttr('id').show();
    }
    
    function resetFieldDropdown($queryCondition){
        var $noSelection = $queryCondition.find('.queryTemplateFields option:first');
        $queryCondition.find('.queryTemplateFields').html( $noSelection );
    }
    
    function fillFieldDropdown($queryCondition){
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
})
