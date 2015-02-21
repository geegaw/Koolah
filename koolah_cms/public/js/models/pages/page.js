define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/node',
  'models/core/label',
], function(_, Backbone, koolahToolkit, Node, Label){
  var Page = Node.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahPage',
		    displayLabel: 'Page',
	    },
	    initialize: function(){
	    	
	    },
	    parse: function(data, options) {
	    	Page.__super__.parse.apply(this, arguments);
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		
	});
  
  return Page;
});