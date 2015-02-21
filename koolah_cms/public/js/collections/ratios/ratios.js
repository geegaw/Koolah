define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/ratios/ratio'
], function(_, Backbone, Nodes, Ratio){
  	var Ratios = Nodes.extend({
    	model: Ratio,
    	comparator: 'label',
    	defaults:{
    		childClass: 'KoolahRatios'
    	}
	});
  
  	return Ratios;
});