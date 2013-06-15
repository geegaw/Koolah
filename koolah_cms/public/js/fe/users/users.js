$(document).ready(function(){
    var PREFIXES = ['grantablePermission', 'grantableRole', 'userPermission'];
	var $msgBlock = $('#msgBlock');
    
    var userForm = new FormTYPE( $('#userForm'), saveUser );
	//userForm.setResetExlude( 'rolesArea' );
	
	var users = new UsersTYPE();
	users.get(displayUsers, null, $msgBlock);
	
	function saveUser(){
		var user = new UserTYPE();
		user.readForm( $('#userForm') );
		user.save( getAndDisplayUsers, $msgBlock );
		return false;
	}
	function getAndDisplayUsers(  data ){
		users.get(displayUsers, null,  $msgBlock);
		hideForm();
	}
	function displayUsers(){
		var html = '';
		if ( users.users().length ){
			for( var i = 0; i < users.users().length; i++ ){
				var user =  users.users()[i];
				html += user.mkInput();
			}
		}
		$('#usersList ul').html(html);
	}
	
	var roles = new RolesTYPE();
	readRoles();
	addRoleClasses();
	
		
	$('#addNewUser').click(function(){
		$(this).hide();
		resetForm()
		userForm.show();
		return false;
	})
	
	$('#cancel').click(function(){
		$('#addNewUser').show();
		hideForm();
		resetForm();
		return false;
	})
	
	$('#reset').click(function(){ resetForm(); })
	
	$('.edit').live('click', function(){
		$('#addNewUser').show();
		resetForm();
		
		var $parent = $(this).parents( '.user' );
		var id = $parent.find( '.userID' ).val();
		var user = users.find( id  );
		if ( user ){
			user.fillForm();
			if ( user.roles && user.roles.length ){
				for ( var i=0; i< user.roles.length; i++ ){
					var role = user.roles[i];
					var color = getRoleColor( role );
					wrapRole( role, 'userPermission', color );
				}
			}
			userForm.show();
			if( user.isSuper() )
				superDisplay();
			else if( user.isAdmin() )
				adminDisplay();
			
			if( listHas( user.permissions, 'roles_grant' ) )
				handleGrantableRoles( true );
			if( listHas( user.permissions, 'permissions_grant' ) )
				handleGrantablePermissions( true );
		}
		else
			errorMsg( $msgBlock, 'error:  user not found' );
	});
	
	$('.reactivate').live('click', function(){
		var $parent = $(this).parents( '.user' );
		var id = $parent.find( '.userID' ).val();
		var user = users.find( id  );
		if ( user )
			user.reactivate( getAndDisplayUsers, $msgBlock);
		else
			errorMsg( $msgBlock, 'error:  user not found' );
	})
	
	$('.del').live('click', function(){
		var $parent = $(this).parents( '.user' );
		var id = $parent.find( '.userID' ).val();
		var name = $parent.find('.userName').html();
		displayDeleteConfirmation(id, $('#usersList'), name);		
	});
	
	$('#usersList .yes').live('click', function(){
		var id = $(this).attr('id');
		var user = users.find( id  );
		user.del( getAndDisplayUsers, $msgBlock );
		return false;
	});
	
	$('input.userRole').live('click', function(){
		var $this = $(this);
		var $parent = $this.parents('fieldset.role');
		var id = $this.val();
		var color = $parent.find('.roleColor').val();
		
		if ( $this.attr('checked') )
			wrapRole(id, 'userPermission', color);
		else
			unwrapRole('userPermission', color, true);
	})
	
	$('input.grantableRole').live('click', function(){
		var $this = $(this);
		var id = $this.val();
		var $parent = $('#'+id).parents('fieldset.role');
		var color = $parent.find('.roleColor').val();
		
		if ( $this.attr('checked') )
			wrapRole(id, 'grantablePermission', color);
		else
			unwrapRole('grantable', color, true);
	})
	
	
	$('input.role').each(function(){
		var $this = $(this);
		var $parent = $this.parents('fieldset.role');
		var color=$parent.find('.roleColor').val();
		$this.wrap('<span class="roleWrapper '+color+'"></span>')
				.parent().css('background', color);
	})
	
	$('input.permission').click(function(){		var $this = $(this);
		var $parent = $this.parents('fieldset.permission');
		if ( $this.attr('checked') ){
			var roles = getPermissionsRoles( $this );
			if  ( roles && roles.length ){
				for ( var i=0; i < roles.length; i++ ){
					var role = roles[i];
					if ( role && allRoleChecked( role ) ){
						var color =getRoleColor( role );						
						$('#'+role).attr('checked', 'checked');
						wrapRole(role, color);
					}
				}
			}
		}	
		else{
			$parent.find('.roleWrapper').each(function(){
				var $permParent = $(this).parents('fieldset.permission');
				var color = getWrapperColor( $(this) )
				unwrapRole(color, false);
				$('fieldset.role .'+color).parents('fieldset.role').find('input.role').removeAttr('checked');				
			})
		}
			
	}) 
	
	$('#superuser').live('click', function(){
		if ( $(this).attr('checked') )
			superDisplay();
		else
			commonDisplay();
		
	})
	
	$('#admin').live('click', function(){
		if ( $(this).attr('checked') )
			adminDisplay();
		else
			commonDisplay();
		
	})
	
	$('#userPermission_roles_grant').live('click', function(){
		var $this = $(this);
		handleGrantableRoles( $this.attr('checked') );
	});
	
	$('#userPermission_permissions_grant').live('click', function(){
		var $this = $(this);
		handleGrantablePermissions($this.attr('checked'));
	});
	
	function handleGrantableRoles( show ){
		$('fieldset.role.grantableRole').hide();
		if ( show ){
			var granted = getChecked( 'role' );
			if (granted && granted.length){
				for( var i=0; i<granted.length; i++ ){
					var $role = $('#grantable_'+granted[i]);
					var $parent = $role.parents('fieldset.role.grantableRole');
					$parent.show();
				}
				$('#grantableRoles').show();	
			}	
		}
		else
			$('#grantableRoles').hide();
	}
	
	function handleGrantablePermissions( show ){
		$('fieldset.permission.grantablePermission').hide();
		if ( show ){
			var granted = getChecked( 'userPermission' );
			if (granted && granted.length){
				for( var i=0; i<granted.length; i++ ){
					var $permission = $('#grantablePermission_'+granted[i]);
					var $parent = $permission.parents('fieldset.permission.grantablePermission');
					$parent.show();
				}
				$('#grantablePermissions').show();	
			}	
		}
		else{	
			$('#grantablePermissions').hide();
		}

	}
	
	function superDisplay(){
		$('#bottom').hide();
        $('fieldset.role').hide();
        $('#permissionsArea').hide();
        $('#superuser').parents('fieldset.role').show();
	}
	
	function adminDisplay(){
	    superDisplay()
		$('#admin').parents('fieldset.role').show();
	}
	
	function commonDisplay(){
	    $('#bottom').show();
        $('fieldset.role').show();
        $('#permissionsArea').show();
	}
	
	function hideForm(){
	 	$('#addNewuser').show();
	 	resetForm();
		userForm.hide();	
	 }
	 
	 function resetForm(){
	 	userForm.resetForm();
	 	unwrapRoles();
	 	commonDisplay();
	 	$('#grantableRoles').hide();
	 	$('#grantablePermissions').hide();
		//$('#rolesArea input[type=checkbox]').removeAttr('checked');
	 }
	
	function readRoles(){
		userForm.$el.find( 'input.role' ).each(function(){
			var role = new RoleTYPE();
			var $parent = $(this).parents('fieldset.role');
			role.id = $(this).val();
			role.name = $parent.find('label').html();
			role.permissions = $parent.find('.rolePermissions').val();
			roles.append( role );
		})
	}
	
	function addRoleClasses(){
		if ( roles && roles.roles()  && roles.roles().length ){
			for ( var i = 0; i < roles.roles().length; i++ ){
				var role = roles.roles()[i];
				if ( role.permissions && role.permissions.length ){
					var permissions = $.parseJSON(role.permissions);
					for ( var j =0; j < permissions.length; j++ ){
						var permission = permissions[j];
						userForm.$el.find('input.permission[value="'+permission+'"]').addClass( role.id );
					}
				}
			}
		}
	}
	
	function unwrapRole(prefix, color, uncheck){
		$('.'+color).each(function(){
			var $inputParent = $(this).parents('fieldset.'+prefix);
			$inputParent.find('.'+color).children(':first').unwrap();
			$input = $inputParent.find('input.'+prefix);
			if ( uncheck && !$input.parent().hasClass('roleWrapper') )
				$input.removeAttr('checked');	
		})
	}
	
	function unwrapRoles(){
		if ( roles && roles.roles() && roles.roles().length ){
	 		for ( var i=0; i<roles.roles().length; i++ ){
	 			var role = roles.roles()[i].id;
	 			var color = getRoleColor( role );
	 			if (color){
	 			    for(var j=0; j < PREFIXES.length; j++){
	 			        var prefix = PREFIXES[j];
	 			       unwrapRole( prefix, color, true );    
	 			    }
	 				
	 		    } 
	 			$('#'+role).removeAttr('checked');
	 		}
	 	}
	}
	
	function wrapRole(id, prefix, color){
	    if (id && color){
    		$('.'+prefix+'.'+id).attr('checked', 'checked')
    					 .wrap('<span class="roleWrapper '+color+'"></span>')
    					 .parent().css('background', color);
	   }
	}	
	
	function getRoleColor( role ){ return $('#'+role).parents('fieldset.role').find('.roleColor').val(); }
	
	function getWrapperColor( $wrapper ){
		var suspects = $wrapper.attr('class');
		var color = suspects.replace("roleWrapper", '');
		return $.trim(color);		
	}
	
	function getPermissionsRoles( $input ){
		var suspects = $input.attr('class');
		suspects = suspects.replace("permission", '');
		suspects = $.trim(suspects);	
		return suspects.split(' ');
	}
	
	function allRoleChecked( role ){
		var allChecked = true;
		$('input.permission.'+role).each(function(){
			if ( !$(this).attr('checked') ){
				allChecked = false;
				return false;
			}
		})
		return allChecked;		
	}
			 
})