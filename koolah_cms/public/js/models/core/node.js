define([
  'underscore',
  'backbone',
  'models/core/meta'
], function(_, Backbone, Meta){
	var Node = Backbone.Model.extend({
	    defaults: {
	        //id: '',
			//meta: new Meta(),
			//childClass: '',
	    },
	    idAttribute: 'id',
	    url: function(){
	    	var url = AJAX_CONTROLLER+'?className='+this.defaults.childClass;
	    	if (this.id)
	    		url+= '&id='+this.id;  
	    	return url;
	    },
		parse: function(data, options) {
			if (data._id){
				data.id = data._id.$id;
				delete(data._id);
			}
	    	return data;
		}
	});
  
  	return Node;
});