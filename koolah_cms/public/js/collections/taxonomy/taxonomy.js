define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/taxonomy/term'
], function(_, Backbone, Nodes, Term){
  	var Taxonomy = Nodes.extend({
    	model: Term,
    	comparator: 'order',
    	defaults:{
    		childClass: 'KoolahTaxonomy'
    	},
    	url: function(){
    		if (this.parentId)
	    		return AJAX_CONTROLLER+'?className='+this.defaults.childClass+'&query[parentID]='+this.parentId;
	    	return AJAX_CONTROLLER+'?className='+this.defaults.childClass+'&query[parentID]=';
	    },
	});
  
  	return Taxonomy;
});