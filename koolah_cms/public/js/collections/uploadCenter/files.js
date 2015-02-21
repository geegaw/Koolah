define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/uploadCenter/file'
], function(_, Backbone, Nodes, File){
  	var Files = Nodes.extend({
    	model: File,
    	comparator: 'label',
    	defaults:{
    		childClass: 'KoolahFiles'
    	},
	});
  
  	return Files;
});