var dropped = false;

$(document).ready(function(){
   
    var $msgBlock = $('#msgBlock');
    
    //var mainTabs = new TabSection($('.tabSection:first'));
    
    var template = new TemplateTYPE();    
    template.parent.id = $('#templateID').val();    
    if ( template.parent.id )
        template.get( fillForm, $msgBlock );
    else{
        template.readForm( $('#templateSection') );
        $('.section:first').attr('id', template.sections.templateSections[0].jsID);
        if ( template.templateType != 'field' )
            $('.tab:first a').attr('href', template.sections.templateSections[0].jsID);
        refreshSections();
    }


    var newTemplateNameForm = new FormTYPE( $('#newTemplateName'), null );         
    var newFieldForm = new FormTYPE( $('#newFieldForm'), null );
    var newSectionForm = new FormTYPE( $('#newSectionName'), null );
    
    var overlay = new Overlay( $('body'), 'fixed' );
    
    $('#save').click(function(){
        if( newTemplateNameForm.validate() ){
            template.label.label = $.trim( $('#templateName').val() );       
            template.save(updateForm, $msgBlock);
console.log( template )    
        }
        return false;
    })
    
    
    $('.addField').live('click', function(){
        var $section = $(this).parents('.section');
        showAddNewFieldForm( $section );
    });
    
    $('#fieldType').change(function(){
        $('.furtherInfo').hide();
        switch( $(this).val() ){
            case 'custom':
                $('#custom').show();
                break;
            case 'dropdown':
                $('#dropdown').show();
                break;
            case 'query':
                $('#queryType').show();
                var query = new QueryTYPE();
                break;
            case 'file':
                $('#fileType').show();
                break;
            case 'date':
                $('#dateType').show();
                break;
            default: 
                break;
        }
    });
    
    $('#addNewFieldNo').click(function(){
        var $section = $(this).parents('.section');
        resetAddField( $section );
        return false;
    });
    
    $('#addNewFieldYes').click(function(){
        if ( newFieldForm.validate() )
        {
            var $section = $(this).parents('.section');
            
            if ( $('#edittingField').val() )
                var field = template.sections.find( $section.attr('id') ).fields.find( $('#edittingField').val() );
            else
                var field = new FieldTYPE();
            field.readForm();      
            
            if ( $('#edittingField').val() ){
                var $editting = $('#'+ $('#edittingField').val() );
                $editting.replaceWith( field.mkCapsule() )
            }
            else{

console.log($section.attr('id'));
console.log(template)        
console.log( template.sections.find( $section.attr('id') ) )
                mkNewField( $section, field );
                template.sections.find( $section.attr('id') ).fields.append( field );
            }
            resetAddField( $section );
console.log(template)
        }
        return false;
    });
    
    $('.editField').live('click', function(){
        var $field = $(this).parents('.field');
        var $section = $(this).parents('.section');
        var field = template.sections.find( $section.attr('id') ).fields.find( $field.attr('id') );
        field.fillForm( $('#newFieldForm') );
        showAddNewFieldForm( $section );
        $('#edittingField').val( $field.attr('id') );
    });
    
    $('.delField').live('click', function(){
        var $section = $(this).parents('.section');
        var $field = $(this).parents('.field');
        $field.remove();
        template.sections.find( $section.attr('id') ).fields.remove( $field.attr('id') );
console.log(template)        
    });
    

    function updateForm( data ){
        if ( !$('#templateID').val()){
            template.parent.id = data.id;
            $('#templateID').val( data.id );
console.log( template );            
        }
    }
    
    function fillForm(){
        $('.section:first').remove();
        if ( template.templateType != 'field' )
            $('.tab:first').remove();
        template.fillForm();
        $('.section:first').addClass('active').show();
        if ( template.templateType != 'field' )
            $('.tab:first').addClass('active');
        refreshSections();
        
console.log(template)        
    }


    /********
     * Fields
     */
    
    
    function showAddNewFieldForm( $section ){
        $section.find('.addField').hide();
        $('#newFieldForm').appendTo( $section.find('.newField ') );
        overlay.open(function(){
            $section.find('.newField ').show();    
        });        
    }
    
    function resetAddField( $section ){
        newFieldForm.resetForm();        
        $section.find('.addField').show();
        $('#edditingField').val('');
        $('#newFieldForm').appendTo( $('#newFieldFormHolder') );
        $('.furtherInfo').hide();           
        $section.find('.newField ').hide();
        overlay.close();
    }
    
    function mkNewField( $section, field ){
        var $fields = $section.find('.fields'); 
        $fields.append( field.mkCapsule() );
        mkFieldsSortable();
    }
    
    var $previousParent = null;
    function mkFieldsSortable(){
        $('.fields').each(function(){
          $(this).sortable({
                connectWith: '.tab',
                start: function(){ $previousParent = $(this).parents('.section'); },
                stop: sortField 
            });
        });
    }
    
    function mkTabSortable(){
        $('.tab').each(function(){
          var $this = $(this);
          $this.sortable({
                items: null,
                receive: mvField  
            });
        });
    }
    
    function mkTabsSortable(){
        $('#tabNames').sortable({
            //look here if error
            items: '.tab:not(#addSection)',
            stop: function(event, ui){
                template.sections.refresh($('#tabNames'));
console.log(template);                
            }
        });
    }

    function sortField( event, ui ){
        var $el = $('#'+ui.item[0].id );
        var $section = $(this).parents('.section');
        template.sections.find( $section.attr('id') ).fields.update( $section );
console.log(template)        
    }
    
    function mvField(event, ui){
        var mvedToID = $(this).find('a').attr('href');
        var $el = $('#'+ui.item[0].id );
        var $section = $('#'+mvedToID);
        $el.appendTo( $section.find('.fields') );
        var field = template.sections.find( $previousParent.attr('id') ).fields.find( $el.attr('id') );
        template.sections.find( $previousParent.attr('id') ).fields.remove( $el.attr('id') );
        template.sections.find( mvedToID ).fields.append( field );
console.log(template);
                
    }

    
    /*******/
   
   
    /********
     * Sections
     */
    
    $('#addSection').click(function(){
        $('#newSectionNameHolder').toggle();
        resetNewSectionForm()
        return false;    
    })
    
    $('#cancelNewSection').click(function(){
        $('#newSectionNameHolder').hide();
        resetNewSectionForm()
        return false;    
    })
    
    $('#addNewSection').click(function(){
        if ( newSectionForm.validate()  ){
            if ( $('#edittingSection').val() ){
                var section = template.sections.find( $('#edittingSection').val() );
                section.name = $.trim( $('#sectionName').val() );
                $('.tab a[href="'+section.jsID+'"]').html( section.name );
            }
            else{
                var section = new TemplateSectionTYPE();
                section.name = $.trim( $('#sectionName').val() );
                template.sections.append( section );
                mkNewSection( section );
            }
            resetNewSectionForm()
            $('#newSectionNameHolder').hide();
console.log( template );            
        }
        return false;    
    })
    
    $('.tab a').live('click', function(){
        var id = $(this).attr('href');
        $('.tab.active').removeClass('active');
        $(this).parents('.tab').addClass('active');
        $('.section.active').hide().removeClass('active');
        $('#'+id).show().addClass('active');
        refreshTabsLayers();
        return false;
    })
    
    $('.tab').live('dblclick', function(){
        $('#newSectionNameHolder').toggle();
        newSectionForm.resetForm();
        var section = template.sections.find( $(this).find('a').attr('href') );
console.log( section )
        $('#sectionName').val( section.name );
        $('#edittingSection').val( section.jsID );
        $('#addNewSection').val( 'Change Name' );
        return false;
    })
    
    
    $('.delSection').live('click', function(){
        if ( template.sections.count() > 1){
            var $section = $(this).parents('.section');
            template.sections.remove( $section.attr('id') );
            var $tab = $('a[href="'+$section.attr('id')+'"]').parents('.tab')
            var isActive = $tab.hasClass('active'); 
            $tab.remove();
            refreshTabsLayers();
            $section.remove();
            if (isActive ){
                $('.tab:first').addClass('active');
                $('.section:first').show().addClass('active');
            }
console.log(template)        
        }
        else{
            errorMsg( $msgBlock, 'There must be at least one tab.', true );
        }
        
    });
    
    function mkNewSection( section ){
        $('#addSection').before( section.mkTab() );
        $('#sections').append( section.mkInput() );
        refreshSections()
    }
    
    function refreshSections(){
        mkFieldsSortable();
        mkTabSortable();
        mkTabsSortable();
        refreshTabsLayers();
    }
    
    function resetNewSectionForm(){
        newSectionForm.resetForm();
        $('#addNewSection').val( 'Add New Section' );
        $('#edittingSection').val( null );    
    }
    /*******/
});

