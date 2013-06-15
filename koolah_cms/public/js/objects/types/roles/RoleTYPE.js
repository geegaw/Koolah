function RoleTYPE(){
	this.parent = new Node( 'KoolahRole' );
	this.name = '';
	this.ref = '';
	this.permissions = [];
		var self = this;
	
	/**
	 * parent extensions
	 */
	this.save = function( callback, $el ){ self.parent.save( self.toAJAX(), null,  callback, $el ); }
	this.get = function( callback, $el ){ self.parent.get( self.fromAJAX, callback, $el ); }	
	this.del = function( callback, $el ){ self.parent.del(null, callback, $el ); }
	this.getID = function(){ return self.parent.getID(); }
	this.equals = function( role ){ return self.parent.equals( role ); }
	/***/

	/**
	 * methods
	 */
	this.fromAJAX = function( data ){
		self.name = data.label;
		self.ref = data.ref;
		self.permissions = data.permissions;
	}

	this.toAJAX = function(){
		var tmp = {}
			tmp.label = self.name;
			tmp.ref = self.ref;
			tmp.permissions = self.permissions;
		return tmp;
	}
	
	this.mkInput = function(){
		var html = '';
		html+= '<li class="role">';
		html+=		'<span class="roleName" date-id="'+self.getID()+'">'+self.name+'</span>';
		html+=  	'<input type="hidden" class="roleID" value="'+self.getID()+'" />';
		html+=		'<span class="commands">';
		html+= 		'<button class="edit">edit</button>';
		html+= 		'<button class="del">del</button>';
		html+=		'</span>'; 
		html+= '</li>';
		return html;
	}
	
	this.readForm = function( $form ){
		self.parent.id = $('#roleID').val();
		self.name = $('#roleName').val();
		
		self.permissions = [];
		$form.find('.permission:checked').each(function(){
            console.log($(this))
            self.permissions[ self.permissions.length ] = $(this).val();
		})
	}
	
	this.fillForm = function(){
		$('#roleID').val( self.parent.id );
		$('#roleName').val( self.name );
		if ( self.permissions.length ){
			for ( var i=0; i < self.permissions.length; i++ )
				$('#'+self.permissions[i]  ).attr( 'checked', 'checked' );
		}
	}

	/***/
	
}