define([
  'underscore',
  'backbone',
  'models/templates/field'
], function(_, Backbone, Field){
  	var Fields = Backbone.Collection.extend({
    	model: Field,
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
  
  	return Fields;
});