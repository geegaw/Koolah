define([
  'underscore',
  'backbone',
  'models/templates/section',
], function(_, Backbone, Section){
  	var Sections = Backbone.Collection.extend({
    	model: Section,
    	comparator: 'order',
    	isValid: function(){
    		_.each(this.models, function(model){
    			if (!model.isValid()){
    				this.validationError = model.validationError;
    				return false;
    			}
    		});
    		return true; 
    	}
	});
  
  	return Sections;
});