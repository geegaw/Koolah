define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/node',
  'models/core/label',
], function(_, Backbone, koolahToolkit, Node, Label){
  var User = Node.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahUser',
		    displayLabel: 'User',
	    },
	    initialize: function(){
	    },
	    parse: function(data, options) {
	    	User.__super__.parse.apply(this, arguments);
	    	this.set('label', data.name);
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			if (!this.get('username'))
				errors.push( 'username/email is required' );
			if (!this.get('name'))
				errors.push( 'name is required' );
			
			var password = this.get('pass1'); 
			if (password){
				if (password.length < 8)
					errors.push( 'password is not long enough, must be at least 8 characters' );
				else if (!password.match(/\d+/g))
					errors.push( 'password must contain at least 1 number' );
				else if (password != this.get('pass2'))
					errors.push( 'passwords do not match' );
				else{
					this.set({password : password});
					this.unset('pass1');
					this.unset('pass2');
				}
			}
			else if (!this.get('password'))
				errors.push( 'password is required' );	
			
			var roles = this.get("roles");
			var permissions =  this.get("permissions");
			if ((!roles || !roles.length) && (!permissions || !permissions.length))
				errors.push( 'you must grant the user at least one permission' );
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		
	});
  
  return User;
});