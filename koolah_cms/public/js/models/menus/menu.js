define([
//  'underscore',
  'backbone',
//  'toolkit/toolkit',
  'models/core/node',
  'collections/menus/menus'
//  'models/core/label',
//], function(_, Backbone, koolahToolkit, Node, Label, Menus){
], function(Backbone, Node, Menus){
  var Menu = Node.extend({
	    defaults: {
		    childClass: 'KoolahMenu',
		    displayLabel: 'Menu',
	    },
		validate: function(attrs, options){
			var errors = [];
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		
	});
  
	return Menu;
});