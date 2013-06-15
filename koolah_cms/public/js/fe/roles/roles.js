$(document).ready(function(){
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
		$('#rolesList ul').html(html);
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