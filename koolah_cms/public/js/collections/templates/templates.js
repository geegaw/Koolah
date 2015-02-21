define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/templates/template'
], function(_, Backbone, Nodes, Template){
  	var Templates = Nodes.extend({
    	model: Template,
    	comparator: 'label',
    	defaults:{
    		childClass: 'KoolahTemplates'
    	},
    	url: function(){
    		if (this.templateType)
	    		return AJAX_CONTROLLER+'?className='+this.defaults.childClass+'&query[templateType]='+this.templateType;
	    	return AJAX_CONTROLLER+'?className='+this.defaults.childClass;
	    },
	});
  
  	return Templates;
});