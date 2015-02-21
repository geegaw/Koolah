define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/roles/role'
], function(_, Backbone, Nodes, Role){
  	var Roles = Nodes.extend({
    	model: Role,
    	comparator: 'label',
    	defaults:{
    		childClass: 'KoolahRoles'
    	}
	});
  
  	return Roles;
});