define([
  'underscore',
  'backbone',
  'models/roles/permission'
], function(_, Backbone, Permission){
  	var Permissions = Backbone.Model.extend({
//    	model: Permission,
    	comparator: 'label',
    	url: function(){
	    	return AJAX_CONTROLLER+'?className=KoolahPermissions';
	    },
	    parse: function(data){
			if (data && data.nodes){
				return data.nodes;
			}
			return [];
		},
    	initialize: function(){
    		var self = this;
    		this.fetch().done(function(){
    			console.log(self.models);
    		});
    	}
	});
  
  	return Permissions;
});