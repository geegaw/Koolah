define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/menus/menu'
], function(_, Backbone, Nodes, Menu){
  	var Menus = Nodes.extend({
    	model: Menu,
    	comparator: 'order',
    	defaults:{
    		childClass: 'KoolahMenus'
    	},
    	url: function(){
    		if (this.parentId)
	    		return AJAX_CONTROLLER+'?className='+this.defaults.childClass+'&query[parentID]='+this.parentId;
	    	return AJAX_CONTROLLER+'?className='+this.defaults.childClass+'&query[parentID]=';
	    },
	});
  
  	return Menus;
});