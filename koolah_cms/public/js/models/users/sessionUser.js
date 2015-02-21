define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/users/user',
], function(_, Backbone, koolahToolkit, User){
  var SessionUser = User.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahSessionUser',
		    displayLabel: 'SessionUser',
	    },
	    initialize: function(){
	    	_.bindAll(this, 'can', 'isAdmin', 'isSuper');
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
			
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		can: function(perm){
			if (this.isAdmin())
				return true;
			if (typeof perm == 'string')
				return _.indexOf( this.get('permissions'), perm ) >= 0;
			return theycan =  _.intersection(this.get('permissons'), perm);
		},
		isAdmin: function(){
			return this.get('isAdmin');
		},
		isSuper: function(){
			return this.get('isSuper');
		}
		
  });
  
  return SessionUser;
});