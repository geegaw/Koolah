define([
  'underscore',
  'backbone',
  'collections/core/modificationHistory'
], function(_, Backbone, ModificationHistory){
	var Meta = Backbone.Model.extend({
	    defaults: {
	        created_by: '',
		    created_at: '',
		    modificationHistory: new ModificationHistory()
	    },
	    initialize : function () {
			
		}
	});
  
  	return Meta;
});