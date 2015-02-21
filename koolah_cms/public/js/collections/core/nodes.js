define([
  'underscore',
  'backbone',
  'models/core/node'
], function(_, Backbone, Node){
	var Nodes = Backbone.Collection.extend({
	    model: Node,
	    defaults: {
	    	total: 0,
	    }, 
	    url: function(){
	    	return AJAX_CONTROLLER+'?className='+this.defaults.childClass;
	    },
	    //comparator: function(property){
		//	return property.attributes._id.$id;
		//}
		initialize: function () {
		},
		parse: function(data){
			if (data){
				this.total = data.total ? data.total : data.nodes ? data.nodes.length : 0;
				return data.nodes;
			}
			return [];
		},
		numPages: function(){
			var total = this.total;
			if (total){
				var pages = parseInt(total / MAX_PER_PAGE);
				if (total % MAX_PER_PAGE > 0)
					pages++;
				return pages;
			}
			return 1;
		}
	});
  
  	return Nodes;
});