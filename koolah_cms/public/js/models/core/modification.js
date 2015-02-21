define([
  'underscore',
  'backbone',
], function(_, Backbone){
	var Modification = Backbone.Model.extend({
	    defaults: {
	        modifiedBy: '',
		    modifiedOn: ''
	    }
	});
  
  	return Modification;
});