var NAV_DURATOIN = 450;

$(document).ready(function(){
    $('.hide').hide().removeClass('hide');    
    centerEls();    
    
    /*
    $('.no').live( 'click', function(){
        closeOverlay();
        return false;
    });
    */
    
    $('#globalNav').click(function(){
        if ( $('#mainNav').is(':visible') )
             $('#mainNav').animate({ left: '-150px' }, NAV_DURATOIN, function(){ $(this).hide() } );
        else
            $('#mainNav').show().animate({ left: 0  }, NAV_DURATOIN );
        
    })
    
    $('.subMenuTrigger').click(function(){ 
        //$('.menuItem a.active').removeClass( 'active' );
        //$(this).toggleClass('active');
        
        var $subNav = $(this).parents('.menuItem:first').find('.subMenu');
        if ( $subNav.is(':visible') )
             closeSubnav($subNav);
        else{
            if( $('.subMenu:visible').length )
                closeSubnav( $('.subMenu:visible'), function(){ openSubnav($subNav) } )
            else
                openSubnav($subNav);
        }        
        function openSubnav($el){ $el.show().animate({ left: '150px'  }, NAV_DURATOIN ); }
        function closeSubnav($el, callback){ 
            $el.animate({ left: '0' }, NAV_DURATOIN, function(){ 
                $(this).hide(); 
                if (callback)
                    callback(); 
            }); 
        }
    });
    
    /*
    $('.menuItem').hoverIntent(
    	function(){ $(this).find('.subMenu').show( 350 ); },
    	function(){ $(this).find('.subMenu').hide( 350 ); }
    );
    */
   
    $('.helpTrigger').live('click', function(){
    	var $parent = $(this).parents('.help');
    	$parent.find('.helpArea')
    		   .show()
    		   .animate({
    		   		height: '75px',
    		   		width: '300px',
    		   }, 500);
    	return false;
    })
    $('.helpTriggerClose').live('click', function(){
    	var $parent = $(this).parents('.help');
    	$parent.find('.helpArea')
    		   .animate({
    		   		height: '13px',
    		   		width: '10px',
    		   }, 500, function(){
    		   		$(this).hide();
    		   })
    	return false;
    })
    
    function closeHelp( $help ){
    	$help.find('.helpArea')
    		   .animate({
    		   		height: '13px',
    		   		width: '10px',
    		   }, 
    		   500, 
    		   function(){
    		   		$(this).hide();
    		   });
	}
});



function centerEls(){
    $('.center').each( function(){
       var $this = $(this);
       var $parent = $this.parent();
       var parentWidth = $parent.outerWidth();
       var parentHeight = $parent.outerHeight();
       var thisWidth = $this.outerWidth();
       var thieHeight = $this.outerHeight(); 
       var marginLeft = Math.floor( parentWidth / 2  ) - Math.floor( thisWidth / 2  );
       var marginTop = Math.floor( parentHeight / 2  ) - Math.floor( thieHeight / 2  );
       
       $this.css({
                    'margin-left':  marginLeft,
                    'margin-top':  marginTop
                }); 
    });
}

function centerHorizEls(){
    $('.centerHoriz').each( function(){
       var $this = $(this);
       var $parent = $this.parent();
       var parentWidth = $parent.outerWidth();
       var thisWidth = $this.outerWidth();
       var marginLeft = Math.floor( parentWidth / 2  ) - Math.floor( thisWidth / 2  );
       
       $this.css({
                    'margin-left':  marginLeft,
                }); 
    });
}

function centerVertEls(){
    $('.centerVert').each( function(){
       var $this = $(this);
       var $parent = $this.parent();
       var parentHeight = $parent.outerHeight();
       var thieHeight = $this.outerHeight(); 
       var marginTop = Math.floor( parentHeight / 2  ) - Math.floor( thieHeight / 2  );
       
       $this.css({
                    'margin-top':  marginTop
                }); 
    });
}

/*
function displayDeleteConfirmation(id, $el, toDelete)
{
    displayConfirmationMsg(id, $el, 'Are you sure you want to delete '+toDelete+'?', 'YES Delete', "NO Don't Delete");
}
function displayConfirmation(id, $el)
{
   displayConfirmationMsg(id, $el, 'Are you sure?', 'YES', 'NO');
}
function displayConfirmationMsg(id, $el, msg, yes, no)
{
    $('#overlay').remove();
    $('#overlayBox').remove();
    var html =''+
    '<div id="overlay"></div>'+
    '<div id="overlayBox">'+
        '<div class="deleteMsg">'+msg+'</div>'+
        '<div class="options">'+
            '<a href="#" class="yes" id="'+id+'">'+yes+'</a>'+
            '<a href="#" class="no">'+no+'</a>'+
        '</div>'+
    '</div>';
    $el.append(html);
}
*/
function closeOverlay()
{
    $('#overlay').fadeOut( 400 );
    $('#overlayBox').fadeOut( 400, function(){
        $('#overlay').remove();
        $('#overlayBox').remove();    
    });
}

function compareArrays( arr1, arr2 )
{
    var combined = [];
    if ( arr1 && arr1.length && arr2 && arr2.length )
    {
        if ( arr1.length > arr2.length )
        {
            var t = arr1;
            arr1 = arr2;
            arr2 = t;
        }
        
        for ( var i = 0; i < arr2.length; i++ )
        {
            for (var j = 0; j < arr1.length; j++)
            {
                if ( arr1[j].id == arr2[i].id )
                {
                    combined[ combined.length ] = arr1[j];
                    arr1.splice(j, 1);
                    continue;
                }
            }
        }              
    }           
    return combined;
}

/*
function mkDraggable( $els, helperFn ){
	$els.each(function(){
      var $this = $(this);
      $this.draggable({ helper: helperFn });
 });
}
*/
function mkDroppable( $els, dropFn ){
	$els.each(function(){
	  var $this = $(this);
	  $this.droppable({ drop: dropFn });
  	});
}    

/*

function mkSortable( $els ){
	if ( $els.length === 1 ){
		$els.sortable();
	}
	else if ( $els.length > 1 ){
		$els.each(function(){
	      var $this = $(this);
	      $this.sortable();
	  	});
  	}
}    
*/
function successMsg( $el ){
	$el.removeClass('error').addClass('success').html( 'Success' ).show();  
    setTimeout(function(){
              $el.fadeOut(1500);
          }, 2000);
}

function errorMsg( $el, msg, fadeout ){
	$el.removeClass('success').addClass('error').html( 'Error: '+msg ).show();
	if ( fadeout ){
    	setTimeout(function(){
                  $el.fadeOut(1500);
              }, 2000);
    }
}

function filterList( list, suspect, by ){
    var results = [];
    if ( list && list.length ){
        for ( var i=0; i<list.length; i++ ){
            if ( typeof list[i] == 'object' ){
                if ( by == 'regex' && list[i].regex != undefined){
                    if ( list[i].regex( suspect ) ){
                         results[results.length] = list[i];
                    }                         
                }
                else if( by == 'exact' && list[i].compare != undefined){
                    if ( list[i].compare( suspect ) == 'equals' )
                         results[results.length] = list[i] 
                }    
            }
            else{
                if ( by == 'regex'){
                    suspect=new RegExp( suspect );
                    if ( suspect.test( list[i] ) )   
                        results[results.length] = list[i]
                }
                else if( by == 'exact'){
                    if ( list[i] == suspect )
                         results[results.length] = list[i] 
                }
            }
        }
    }
    return results;
}

function findInList( list, suspect ){
	if ( list && list.length ){
		for ( var i=0; i<list.length; i++ ){
			if ( typeof list[i] == 'object' && list[i].compare != undefined){
			    if ( list[i].compare( suspect ) == 'equals' )
			        return list[i]; 
			}
			else{
    			if ( list[i] == suspect )
    				return list[i];
		    }
		}
	}
	return null;
}


function findPosInList( list, suspect ){
    if ( list && list.length ){
        for ( var i=0; i<list.length; i++ ){
            if ( typeof list[i] == 'object' && list[i].compare != 'undefined'){
                if ( list[i].compare( suspect ) == 'equals' )
                    return i; 
            }
            else{
                if ( list[i] == suspect )
                    return i;
            }
        }
    }
    return -1;
}
function listHas( list, suspect ){ return Boolean( findInList( list, suspect ) ); }

function getChecked( className ){
	var checked = [];
	$('input.'+className+':checked').each(function(){ 
		checked[checked.length]=$(this).val(); 
	});
	return checked;
}

function refreshTabsLayers(){
    var left = 0;
    var zindex = $('.tab').length;
    $('.tab').each(function(){
        if ( $(this).hasClass('active') ){
            $(this).css({
                'left': left+'px',
                'z-index': ($('.tab').length + 1) 
            });
        }
        else{
            $(this).css({
                'left': left+'px',
                'z-index': zindex 
            });
        }
        left-=4;
        zindex--;
    })
}
