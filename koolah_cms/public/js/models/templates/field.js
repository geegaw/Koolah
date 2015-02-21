define([
  'underscore',
  'backbone',
  'toolkit/toolkit',
], function(_, Backbone, koolahToolkit){
  var Field = Backbone.Model.extend({
	    initialize: function(){
	    },
	    parse: function(data, options) {
	    	return data;
		},
		validate: function(attrs, options){
			var errors = [];
			if (!this.get('label'))
				errors.push('Name is required');
			var type = this.get('type');
			if (!type || type == 'no_selection')
				errors.push('Field type is required');
			else{
				var options = this.get('options');
				switch (type){
					case 'dropdown':
						if (!options)
							errors.push('Dropdown options are required');
						break;
					case 'file':
						if (!options || options == 'no_selection')
							errors.push('A file type is required');
						break;
					case 'custom': 
						if (!options || options == 'no_selection')
							errors.push('A custom field is required');
						break;
					case 'query': 
						//if (!options || options == 'no_selection')
						//	errors.push('A custom field is required');
						break;
				}
			}
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>'; 
		},
		
	});
  
  return Field;
});