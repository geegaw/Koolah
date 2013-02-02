/*
function RoleTYPE(){
	this.id = '';
	this.name = '';
	this.permissions = [];
	this.collection = 'roles';
	var self = this;
	
	this.readForm = function( $form ){
		self.id = $('#roleID').val();
		self.name = $('#roleName').val();
		
		self.permissions = [];
		$form.find('.permission:checked').each(function(){
					self.permissions[ self.permissions.length ] = $(this).val();
		})
	}
	
	this.fillForm = function(){
		$('#roleID').val( self.id );
		$('#roleName').val( self.name );
		if ( self.permissions.length ){
			for ( var i=0; i < self.permissions.length; i++ )
				$('#'+self.permissions[i]  ).attr( 'checked', 'checked' );
		}
	}
	
	
	this.save = function( callback, $el ){
		$.ajax({
	                url: '/ajax/saveRole',
	                type: 'POST',
	                data: {  
	                            'id': this.id,
	                            'name': this.name,
	                            'permissions': this.permissions
	                      },
	                async: false,
	                dataType: 'json',
	                success: function(data){
	                		if (data.status){
	                			successMsg( $el );
	                            callback( data );
	                       }
	                        else
	                        	errorMsg( $el, data.msg );	                            
	                    }
	           });
	
	}
	
	this.del = function(callback, $el){
		deleteNode( this.id, this.collection, callback, $el )
	}
	
	this.mkInput = function(){
		var html = '';
		html+= '<li class="role">';
		html+=		'<span class="roleName">'+self.name+'</span>';
		html+=  	'<input type="hidden" class="roleID" value="'+self.id+'" />';
		html+=		'<span class="roleOptions">';
		html+= 		'<button class="edit">edit</button>';
		html+= 		'<button class="del">del</button>';
		html+=		'</span>'; 
		html+= '</li>';
		return html;
	}
}
*/