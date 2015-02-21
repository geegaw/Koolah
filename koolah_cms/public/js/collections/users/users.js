define([
  'underscore',
  'backbone',
  'collections/core/nodes',
  'models/users/user'
], function(_, Backbone, Nodes, User){
  	var Users = Nodes.extend({
    	model: User,
    	comparator: 'label',
    	defaults:{
    		childClass: 'KoolahUsers'
    	}
	});
  
  	return Users;
});