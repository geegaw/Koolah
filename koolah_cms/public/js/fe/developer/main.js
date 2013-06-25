$(document).ready(function(){
    var FADE_TIME = 350;
    var $msgBlock = $('#msgBlock');
    var templates = new TemplatesTYPE();
    templates.get(initTemplates, null, $msgBlock);
    
    var overlay = new Overlay( $('body'), FADE_TIME, 'fixed' );
    overlay.closeFn = closeForm;
    overlay.openFn = showForm;
    
    var importForm = new FormTYPE( $('#importForm') );
    var fileImport = new ImportFile();
    fileImport.succesFn = function(){ 
        templates = new TemplatesTYPE();
        templates.get(initTemplates, null, $msgBlock); 
    }
    
    function initTemplates(){
        templates.sort();
        displayAllTemplates( templates );
    }1
    
    function displayAllTemplates( templatesList ){
        var templateTypes = new TemplateTYPE().getTypes();
        if ( templateTypes && templateTypes.length ){
            for (var i=0; i < templateTypes.length; i++){
                var type = templateTypes[i];
                displayTemplates( templatesList[type], type );
            }
        }
    }
    
    function displayTemplates( templates, type ){
        $('#'+type+'sList li').remove()
        if ( templates && templates.length ){
            for (var i=0; i < templates.length; i++){
                var template = templates[i];
                $('#'+type+'sList ul').append( template.mkList() ); 
            }
        }
    }
    
    $('.template .del').live('click', function(){
        var name = $(this).parents('li').find('a').html();
        var $section = $(this).parents('.tabSection');
        var msg = new Comfirmation('delete');
        msg.displayDeleteConfirmation( 'delComfirm', $section, name );
        $('#activeTemplate').val( $(this).attr('href') );
        return false;
    });
    
    $('.no').live('click', function(){
        $('#activeTemplate').val( null );
        return false;
    });
    
    $('#delComfirm').live('click', function(){
        var templateID = $('#activeTemplate').val();
        var template = templates.find( templateID );
        template.del( removeTemplate, $msgBlock );
        return false;
    });
    
    function removeTemplate(){
        var templateID = $('#activeTemplate').val();
        templates.remove(templateID);
        $('#'+templateID).remove();
        $('#activeTemplate').val( null );
        closeOverlay();
    }
    
    
    $('.typeFilter').click(function(){
        var $this = $(this);
        var type = $this.val();
        if ( $this.attr('checked') == 'checked' )
            $('#'+type+'Section').show();
        else
            $('#'+type+'Section').hide();
    })
    
    $('#templateSearch').keyup(function(){
        var $this = $(this);
        var q = $this.val();
        if ( q.length >= 3 ){
            var results = templates.filter(q, 'regex');
            results.sort();        
            displayAllTemplates( results );
        } 
        else
            displayAllTemplates( templates );
    })
    
    $('.import').click(function(){
        overlay.open();
    })
    
    $('#importForm .cancel').click(function(){
        overlay.close();
    })
    
    $('#importForm .save').click(function(){
        fileImport.save();
        return false;
    });
    
    function showForm( showFull ){
        resetForm();
        $('#importForm').fadeIn(FADE_TIME);
    }
    
    function closeForm(){
        resetForm()
        $('#importForm').fadeOut(FADE_TIME);
    }
    
    function resetForm(){
        importForm.resetForm();
    }
})