$(document).ready(function(){
    var $msgBlock = $('#msgBlock');
    var templates = new TemplatesTYPE();
    templates.get(initTemplates, null, $msgBlock);
    
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
        displayDeleteConfirmation( 'delComfirm', $section, name );
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
console.log(template);        
        template.del( removeTemplate, $msgBlock );
        return false;
    });
    
    function removeTemplate(){
        var templateID = $('#activeTemplate').val();
        templates.remove(templateID);
        $('#'+templateID).remove();
        $('#activeTemplate').val( null );
        closeOverlay();
console.log(templates);        
    }
    
    
    $('.type input').live('click', function(){
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
console.log( results );
            displayAllTemplates( results );
        } 
        else
            displayAllTemplates( templates );
    })
})

/*
var templatesCollection = 'templates';

var templatesAll = {};
	templatesAll.page = [];
	templatesAll.widget = [];
	templatesAll.field = [];

var curID = null;

$(document).ready(function(){
    
    init();
	
	$('.tab input').click(function(){
		var show = '#'+$(this).val()+'Section';
		$(show).toggle(250);
	});
	
	$('#templateSearch').keyup(function(){
		var q = $(this).val();
		if ( q.length >=3 ){
			filter( q, 'page' );
			filter( q, 'widget' );
			filter( q, 'field' );
		}
		else
			displayAllTemplates();
	});
	
	$('.delTemplate').live('click', function(){
		var name = $(this).parents('li').find('a').html();
		var $section = $(this).parents('.tabSection');
		displayDeleteConfirmation( 'delComfirm', $section, name );
		curID = $(this).val();
	});
	
	$('.no').live('click', function(){
		curID = null;
		return false;
	});
	
	$('#delComfirm').live('click', function(){
		deleteNode( curID, templatesCollection, getTemplates, $('#templateMsg') );
		curID = null;
		return false;
	});
	
	
});

function init()
{
    getTemplates();   
    $('.tabSection:first').show(); 
}

function filter( q, type ){
	var suspects = templatesAll[type];
	var results = [];
	if ( suspects && suspects.length ){
		var regex = new RegExp( q, 'i' );
		for (var i=0; i< suspects.length; i++){
			var suspect = suspects[i];
			if ( regex.test( suspect.label ) )
				results[ results.length ] = suspect;
		}
	}
	displayTemplates( results, type );
}

function getTemplates(){
	getNodes( 'TemplatesTYPE', handleTemplates, $('#templateMsg') );
}

function handleTemplates( templates ){
	sortTemplates( templates.templates );
	displayAllTemplates();
}

function displayAllTemplates(){
	displayTemplates( templatesAll.page, 'page' );
	displayTemplates( templatesAll.widget, 'widget' );
	displayTemplates( templatesAll.field, 'field' );
}

function sortTemplates( templates ){
	if( templates && templates.length ){
		for(var i=0; i<templates.length; i++ ){
			var template = templates[i];
			var template_type = template.template_type;
			
			templatesAll[template_type].push( template );
		}
	}
}

function displayTemplates( templates, type )
{
    var html = 'no '+type+'s'; 
    if ( templates && templates.length )
    {
        html = '';
        for ( var i = 0; i < templates.length; i++ ){
            var oddeven='odd';
            if ( (i+1)%2 == 0 )
            	oddeven='even'
            html += displayTemplate( templates[i], type, oddeven );
        }
    }       
    $('#'+type+'sList ul').html( html );                
}

function displayTemplate( template, type, oddeven )
{
    var html = '';
    if ( template )
    {
        html = '<li class="'+oddeven+'">';
        html+= '  <a href="template.php?templateType='+type+'&templateID='+getNodeID(template)+'">'+template.label+'</a>';
        html+= '  <button class="del delTemplate" value="'+getNodeID(template)+'">X</button>';
        html+= '</li>';
    }
    return html;
}


*/