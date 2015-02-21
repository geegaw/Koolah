define([
  'underscore',
  'backbone',
  'models/core/modification'
], function(_, Backbone, Modification){
	var ModificationHistory = Backbone.Collection.extend({
	    model: Modification
	});
  
  	return ModificationHistory;
});