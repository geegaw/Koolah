function RolesTYPE(){
	this.parent = new Nodes( 'KoolahRoles' );
	var self = this;

	/**
	 * parent extensions
	 */
	this.get = function( callback, args, $el ){ self.parent.get( self.fromAJAX, callback, args, $el ); }
	this.clear = function(){ self.parent.clear(); }
	this.append = function( user ){ self.parent.append( user ); }
	this.find = function( user ){  return self.parent.find( user ); }
	/***/
	
	/**
	* methods
	*/
	this.roles = function(){ return self.parent.nodes; }
	
	this.fromAJAX = function( response ){
		self.parent.nodes = self.parent.nodes.roles;
		if ( self.parent.nodes && self.parent.nodes.length ){
			var tmp = self.parent.nodes.slice(0);
			self.clear(); 
			for (var i=0; i < tmp.length; i++){
				var node = tmp[i];
				var role = new RoleTYPE();
				role.parent.fromAJAX( node );
				role.fromAJAX( node );
				self.append(role);
			}
		}
	}
	/***/

}