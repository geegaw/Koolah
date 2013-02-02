/*
function RolesTYPE(){
	this.roles = [];
	this.callback = null;
	var self = this;
	
	this.getRoles = function(){ return this.roles; }
	this.setRoles = function( roles ){ this.roles = roles.slice(0); }
	
	this.append = function( role ){
		self.roles[ this.roles.length ] = role;
	}
	
	this.get = function( callback, $el ){
		self.callback = callback;
		getNodes( 'RolesTYPE',  self.handle, $el, null );	
	}
	
	this.handle = function( data ){
		self.clear();
		if (data.roles && data.roles.length){
			var roles = data.roles;
			for( var i=0; i < data.roles.length; i++ ){
				var role = new RoleTYPE();
				role.id = getNodeID( roles[i]) ;
				role.name = roles[i].label;
				role.permissions = roles[i].permissions;
				self.append( role );
			}
		}
		self.callback();
	}
	
	this.clear = function(){
		self.roles = [];
	}
	
	this.find = function( id ){
		if ( self.roles && self.roles.length ){
			for( var i=0; i < self.roles.length; i++){
				if( self.roles[i].id == id )
					return self.roles[i];
			}
		}
		return null;	
	}
	
}
*/