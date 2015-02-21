define([
  'underscore',
  'backbone',
], function(_, Backbone, Nodes){
  	var Sections = Nodes.extend({
    	model: Section,
    	comparator: 'label',
    	defaults:{
    		childClass: 'KoolahPages'
    	}
	});
  
  	return Sections;
});