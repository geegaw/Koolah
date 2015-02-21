define([
  'underscore',
  'backbone',
], function(_,Backbone){
  var Crop = Backbone.Model.extend({
	    defaults: {
		    childClass: 'KoolahCrop',
		    displayLabel: 'Crop',
	    },
	    initialize: function(){
	    },
	    parse: function(data, options){
	    	Crop.__super__.parse.apply(this, arguments);
	    	return data;
	    },
		validate: function(attrs, options){
			var errors = [];
			if (this.id == 'freeForm' && !this.get('name'))
				errors.push('name is required');
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>';
		},

		
	});
  
	return Crop;
});