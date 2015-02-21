define([
  'underscore',
  'backbone',
  'models/ratios/ratioSize'
], function(_, Backbone, RatioSize){
  	var RatioSizes = Backbone.Collection.extend({
    	model: RatioSize,
    	numPages: function(){
    		return 1;
    	}
	});
  	return RatioSizes;
});