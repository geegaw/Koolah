define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/node',
  'models/core/label',
], function(_, Backbone, koolahToolkit, Node, Label){
  var Term = Node.extend({
	    defaults: {
	        $msgBlock : '',
		    childClass: 'KoolahTerm',
		    parentID: null,
	    },
	    initialize: function(){
	    },
	    parse: function(data, options) {
	    	Term.__super__.parse.apply(this, arguments);
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			if (!$.trim(attrs.label).length)
				errors.push('you must provide a name');
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
	});
  
  return Term;
});