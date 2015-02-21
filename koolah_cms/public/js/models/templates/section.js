define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'models/core/meta',
  'collections/templates/fields'
], function(_, Backbone, koolahToolkit, Meta, Fields){
  var Section = Backbone.Model.extend({
	    initialize: function(data){
	    	/*
	    	if (!this.get('meta')){
	    		var meta = new Meta();
	    		this.set({ meta: meta });
	    	}
	    	*/
	    	if (data && data.fields)
	    		var fields = new Fields(data.fields);
	    	else
	    		var fields = new Fields();
	    	this.set({ fields: fields });
	    },
	    parse: function(data, options) {
	    	/*
	    	if (data.meta){
		    	var meta = new Meta(data.meta);
		    	this.set({meta : meta});
		    	delete(data.meta);
	    	}
	    	*/
	    	if (data && data.fields){
		    	var fields = new Fields(data.fields);
		    	this.set({fields : fields});
		    	delete(data.fields);
	    	}
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			var fields = this.get('fields');
			if (!this.get('name'))
				errors.push( 'name is required' );
			if (fields && !fields.isValid())
				errors.push( fields.validationError );
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		getField: function(fieldId){
			var field = null;
			var fields = this.get('fields');
			if (fields)
				field = fields.get(fieldId);
			return field;
		},
	});
  
  return Section;
});