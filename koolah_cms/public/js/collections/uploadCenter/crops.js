define([
  'underscore',
  'backbone',
  'models/uploadCenter/crop'
], function(_, Backbone, Crop){
  	var Crops = Backbone.Collection.extend({
    	model: Crop,
    	comparator: 'label',
	});
  
  	return Crops;
});