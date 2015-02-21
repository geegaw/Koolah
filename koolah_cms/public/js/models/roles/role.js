define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/node',
  'models/core/label',
], function(_, Backbone, koolahToolkit, Node, Label){
  var Role = Node.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahRole',
		    displayLabel: 'Role',
	    },
	    initialize: function(){
	    },
	    parse: function(data, options) {
	    	Role.__super__.parse.apply(this, arguments);
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			if (!this.get('label'))
				errors.push('Name is required');
			if (!this.get('permissions') || this.get('permissions').length < 4)
				errors.push("You must select at least 4 permissions, otherwise we don't believe its worth making this a role");
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		
	});
  
  return Role;
});