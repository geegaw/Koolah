define([
  'underscore',
  'backbone',
], function(_, Backbone){
  
  var Label = Backbone.Model.extend({
	    defaults: {
	        label: '',
		    ref: ''
	    }
	});
  
  return Label;
});