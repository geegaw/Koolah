function UserTYPE(){
	this.parent = new Node( 'KoolahUser' );
	this.name = '';
	this.username = '';
	this.active = true;
	this.roles = [];
	this.permissions = [];
	this.grantableRoles = [];
	this.grantablePermissions = [];
	var self = this;
	
	/**
	 * parent extensions
	 */
	this.save = function( callback, $el ){ self.parent.save( self.toAJAX(), null,  callback, $el );}
	this.get = function( callback, $el ){ self.parent.get( self.fromAJAX, callback, $el ); }	
	this.del = function( callback, $el ){ self.parent.del(null, callback, $el ); }
	this.getID = function(){ return self.parent.getID(); }
	this.equals = function( user ){ return self.parent.equals( user ); }
	/***/

	/**
	 * methods
	 */
	this.fromAJAX = function( data ){
		self.name = data.name;
		self.username = data.username;
		self.active = data.active;
		self.roles = data.roles;
		self.permissions = data.permissions;
		self.grantableRoles = data.grantableRoles;
		self.grantablePermissions = data.grantablePermissions;
	}

	this.toAJAX = function(){
		var tmp = {}
			tmp.name = self.name;
			tmp.username = self.username;
			tmp.active = self.active;
			if ( self.password )
				tmp.password = self.password;
			tmp.roles = self.roles;
			tmp.permissions = self.permissions;
			tmp.grantableRoles = self.grantableRoles;
			tmp.grantablePermissions = self.grantablePermissions;
		return tmp;
	}
	
	this.mkInput = function(){
		var html = '';
		html+= '<li class="user">';
		html+=		'<span class="userName">'+self.name+'</span>';
		html+=  	'<input type="hidden" class="userID" value="'+self.getID()+'" />';
		html+=		'<span class="userOptions">';
		html+= 		'<button class="edit">edit</button>';
		html+= 		'<button class="del">del</button>';
		if ( !self.active )
			html+= 	'<button class="reactivate">reactivate</button>';
		html+=		'</span>'; 
		html+= '</li>';
		return html;
	}
	
	this.readForm = function( $form){
		self.parent.id = $('#userID').val();
		self.username = $('#userName').val();
		self.name = $('#name').val();
		self.password = $('#pass1').val();
		
		self.roles = [];
		self.permissions = [];
		
		if ( $('#superuser').attr('checked') )
			self.roles[0] = 'superuser';
		else if ( $('#admin').attr('checked') )
			self.roles[0] = 'admin';
		else{
			$form.find('.userRole.role:checked').each(function(){
				self.roles[ self.roles.length ] = $(this).val();
			})
			
			$form.find('.userPermission.permission:checked').each(function(){
				if ( !$(this).parent().hasClass('roleWrapper') )
					self.permissions[ self.permissions.length ] = $(this).val();
			})
			
			$form.find('.grantableRole.role:checked').each(function(){
				self.grantableRoles[ self.grantableRoles.length ] = $(this).val();
			})
			
			$form.find('.grantablePermission.permission:checked').each(function(){
				if ( !$(this).parent().hasClass('roleWrapper') )
					self.grantablePermissions[ self.grantablePermissions.length ] = $(this).val();
			})
		}
	}
	
	this.fillForm = function(){
		$('#userID').val( self.getID() );
		$('#userName').val( self.username );
		$('#name').val( self.name );
		
		if ( self.roles && self.roles.length ){
			for ( var i=0; i < self.roles.length; i++ )
				$('#'+self.roles[i]  ).attr( 'checked', 'checked' );
		}
		
		if ( self.permissions && self.permissions.length ){
			for ( var i=0; i < self.permissions.length; i++ )
				$('#userPermission_'+self.permissions[i] ).attr( 'checked', 'checked' );
		}
		
		if ( self.grantableRoles && self.grantableRoles.length ){
			for ( var i=0; i < self.grantableRoles.length; i++ )
				$('#grantable_'+self.grantableRoles[i]  ).attr( 'checked', 'checked' );
		}
		
		if ( self.grantablePermissions && self.grantablePermissions.length ){
			for ( var i=0; i < self.grantablePermissions.length; i++ )
				$('#grantablePermission_'+self.grantablePermissions[i]  ).attr( 'checked', 'checked' );
		}
	}
	
	
	this.reactivate = function( callback, $el ){ 
		self.active = true;
		self.parent.save( self.toAJAX(), null,  callback, $el );
	}
	
	this.hasRole = function( role ){ return listHas( self.roles, role ); }
	this.hasPermission = function( permission ){ return listHas( self.permissions, permission ); }
	this.isSuper = function(){ return self.hasRole( 'superuser' ); }
	this.isAdmin = function(){ return (self.hasRole( 'superuser' ) || self.hasRole( 'admin' )); }
	/***/
	
}