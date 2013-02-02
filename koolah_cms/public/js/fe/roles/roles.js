$(document).ready(function(){
	console.log( 'load' )
  	var $msgBlock = $('#msgBlock');
   
	var roles = new RolesTYPE();
	roles.get(displayRoles, null, $msgBlock);
	
	var roleForm = new FormTYPE( $('#roleForm'), saveRole );
	function saveRole(){
		var role = new RoleTYPE();
		role.readForm( $('#roleForm') );
		role.save( getAndDisplayRoles, $msgBlock );
		return false;
	}
	function getAndDisplayRoles( data ){
		roles.get(displayRoles, null, $msgBlock);
		hideForm();
	}
	function displayRoles(){
		var html = '';
		if ( roles.roles().length ){
			for( var i = 0; i < roles.roles().length; i++ ){
				var role =  roles.roles()[i];
				html += role.mkInput();
			}
		}
		$('#rolesList').html(html);
	}
	
		
	$('#addNewRole').click(function(){
		$(this).hide();
		roleForm.resetForm();
		roleForm.show();
		return false;
	})
	
	$('#cancel').click(function(){
		hideForm();
		return false;
	})
	
	
	$('.edit').live('click', function(){
		var $parent = $(this).parents( '.role' );
		var id = $parent.find( '.roleID' ).val();
		var role = roles.find( id  );
		if ( role ){
			roleForm.resetForm();
			role.fillForm();
			roleForm.show();
		}
		else
			errorMsg( $msgBlock, 'error:  role not found' );
	});
	
	$('.del').live('click', function(){
		var $parent = $(this).parents( '.role' );
		var id = $parent.find( '.roleID' ).val();
		var name = $parent.find('.roleName').html();
		displayDeleteConfirmation(id, $('#rolesList'), name);		
	});
	
	$('#rolesList .yes').live('click', function(){
		var id = $(this).attr('id');
		var role = roles.find( id  );
		role.del( getAndDisplayRoles, $msgBlock );
		return false;
	});
	 
	 function hideForm(){
	 	$('#addNewRole').show();
		roleForm.hide();	
	 }
	 
});






/*    
    var perms;
    updateRoles();
    
    $('#addNewRole a').live('click', function(){
       $('#roleBlock').fadeOut(500, function(){
           $('#msgBlock').hide();
           $('#roleid').val('');
           $('#name').val('');
           $('#roleForm :checkbox').removeAttr('checked');
           $('#roleBlock').fadeIn(500);
           $('#addNewRole').fadeOut(500); 
       });
       return false;
    });

    $('a.edit').live('click', function(){
       var $this = $(this);
       var $parent = $this.parents('li');
       
       perms = JSON.parse( $parent.find('.permissions').html() ).permissions;
       $('#msgBlock').hide();
       $('#addNewRole').fadeIn(500);
       $('#roleBlock').fadeOut( 500, function(){
            $('#roleForm :checkbox').removeAttr('checked');
       
            if (perms)
            {
                var perm;
                for ( var i = 0; i < perms.length; i++ )
                {
                    perm = perms[i];
                    $('#roleForm :checkbox[value='+perm+']').attr('checked', 'checked');     
                }
            }
                  
            $('#roleid').val( $this.attr('href') );
            $('#name').val( $parent.find('.name').html() );
            $('#roleForm input').removeClass('required');
            $('#roleBlock').fadeIn( 500 );
       });
       return false;
    });

    $('#cancel').live('click', function(){
       $('#roleid').val('');
       $('#roleBlock').fadeOut( 250, function(){
            $('#addNewRole').fadeIn(500);     
       });                      
       return false;
    });
    
    $('a.del').live('click', function(){
        var $parent = $(this).parents('li');
        var name = $parent.find('.name').html();
        $('#roleid').val( $(this).attr('href') );
        displayDeleteConfirmation( 'delRole', $('#rolesList'), name );
        return false;
    });
    
    $('#delRole').live('click', function(){
        
        if ( $('#roleid').val().length )
        {
            $.ajax({
                    url: 'ajax/delRole.php',
                    type: 'POST',
                    data: {  
                                'id': $('#roleid').val()
                          },
                    async: false,
                    dataType: 'json',
                    success: function(data){
                            if (data.status== 'success')
                            {
                                closeOverlay();
                                fadeOutForm();
                            }
                            else
                                $('#msgBlock').removeClass('success').addClass('error').html( data.status ).show();
                        }
               })
        }
        return false;
    });
    
    $('#save').live('click', function(){
       $(this).hide(); 
       if ( validateFormAndReport() ) 
       {
           var id = null;
           var name = null;
           if ( $('#roleid') && $('#roleid').val().length )
                id = $('#roleid').val();
           if ( $.trim( $('#name').val() ).length )
                name = $.trim( $('#name').val() );
           
           var perms =[]; 
           $('.perm:checked').each(function(){
                perms[perms.length]=$(this).val();     
           });

           $.ajax({
                url: 'ajax/saveRole.php',
                type: 'POST',
                data: {  
                            'id': id,
                            'name': name,
                            'permissions': perms
                      },
                async: false,
                dataType: 'json',
                success: function(data){
                        if (data.status== 'success')
                            fadeOutForm();
                        else
                            $('#msgBlock').removeClass('success').addClass('error').append( data.status ).show();
                    }
           });
       } 
       $(this).show();
       return false;
    });

    function updateRoles()
    {
         $('#rolesList').hide();
         $.ajax({
                    url: 'ajax/getRolesForm.php',
                    type: 'POST',
                    async: false,
                    dataType: 'html',
                    success: function(html){ 
                        $('#rolesList').html(html);
                        $('#rolesList').show(); 
                    }
               });
    }

    function fadeOutForm()
    {
        $('#roleid').val('');
        $('#msgBlock').removeClass('error').addClass('success').html( 'success' ).show();
        $('#addNewRole').fadeIn(500);
        $('#roleBlock').fadeOut(500);
        updateRoles();
        setTimeout( function(){
                $('#msgBlock').fadeOut(1500);                
            }, 2000);
    }
});

*/