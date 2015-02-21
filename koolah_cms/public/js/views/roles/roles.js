/**
 * @fileOverview defines role view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/defaultView',
  'views/permissions/permissions',
  'collections/roles/roles',
  'models/roles/role',
  'text!templates/roles/roleForm.html',
], function($, _, Backbone, koolahToolkit, DefaultView, PermissionsView, Roles, Role, roleForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var RoleView = DefaultView.extend({
		collection: new Roles(),
		defaults: {
			classname: 'roles',
			title: 'Roles',
			label: 'Roles',
			
		},
		initialize: function(router, sessionUser, args){
			RoleView.__super__.initialize.apply(this, [router, sessionUser, args]);
			this.setElement('#roles');
			_.bindAll(this, 'renderPermissions');			
			
			//set role form data
			this.container.form.inputTemplate = roleForm;
			this.container.form.renderCallback = this.renderPermissions;
			
			this.permissionsView = new PermissionsView();
		},
		renderPermissions: function(role){
			this.permissionsView.setElement( this.container.form.$('.extra') );
			this.permissionsView.render(role);
		},
	});
	return RoleView;
});