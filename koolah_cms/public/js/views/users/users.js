/**
 * @fileOverview defines users view
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
  'collections/users/users',
  'models/users/user',
  'collections/roles/roles',
  'text!templates/users/userForm.html',
  'text!templates/users/userRolesForm.html',
], function($, _, Backbone, koolahToolkit, DefaultView, PermissionsView, Users, User, Roles, userForm, userRoleForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var UsersView = DefaultView.extend({
		collection: new Users(),
		defaults: {
			classname: 'users',
			title: 'Users',
			label: 'Users',
			colors: [ 'black', 'blue', 'red', 'purple', 'yellow', 'grey', 'pink', 'green', 'Boodle', 'Coffee', 'Fiasco', 'Secede' ]
		},
		initialize: function(router, sessionUser, args){
			var self = this;
			this.roleColors = [];
			this.roleColors['superuser'] = self.defaults.colors[0];
			this.roleColors['admin'] = self.defaults.colors[1];
			this.roles = new Roles();
			this.roles.fetch().done(function(){
				_.each(self.roles.models, function(role, i){
					self.roleColors[role.id] = self.defaults.colors[ ((i + 2) % self.defaults.colors.length) ];
				});
			});
			UsersView.__super__.initialize.apply(this, [router, sessionUser, args]);
			this.setElement('#users');
			_.bindAll(this, 'renderPermissions');
			
			//set users form data
			this.container.form.inputTemplate = userForm; 
			this.container.form.renderCallback = this.renderPermissions;
			
			this.permissionsView = new PermissionsView();
		},
		renderPermissions: function(user){
			console.log(user);
			var tmpl = _.template(userRoleForm);
			
			this.container.form.$('.extrainputs').html( tmpl({ roles: this.roles.toJSON(),  model: user.toJSON()}) );
			this.container.form.$('.extrainputs').append('<div class="permissions"></div>');
			
			this.permissionsView.setElement( this.container.form.$('.extrainputs .permissions') );
			this.permissionsView.render(user);
			
			var self = this;
			this.container.form.$('.extrainputs .roles input:checkbox').each(function(i){
				var $this = $(this);
				self.wrapColor($this, self.defaults.colors[ (i % self.defaults.colors.length) ]); 
			});
			
			this.container.form.$('.extrainputs .roles input:checkbox:checked').trigger('change');
		},
		events: {
			'change .roles input:checkbox': 'toggleRolePermissions',
		},
		toggleRolePermissions: function(e){
			e.preventDefault();
			var self = this;
	    	var $this = $(e.currentTarget);
	    	var $fieldset = $this.parents('fieldset:first');
	    	var role = $this.val(); 
	    	var color = this.roleColors[role];
	    	var $checkboxes = null;
	    	var wrap = $this.is(':checked');
	    	
	    	if (role == 'superuser' || role == 'admin'){
	    		$checkboxes = this.container.form.$('.extrainputs .permissions input:checkbox');
	    		var rolesPos = role == 'superuser' ? 0 : 1;
	    		if ($this.is(':checked')){
	    			this.container.form.$('.extrainputs .roles input:checkbox:gt('+rolesPos+')').prop({
	    				checkbox: false,
	    				disabled: true,
	    			});
	    		}
	    		else{
	    			this.container.form.$('.extrainputs .roles input:checkbox:gt('+rolesPos+')').prop({
	    				disabled: false,
	    			});
	    		}
	    	}
	    	else{
	    		role = this.roles.get( $this.val() );
	    		var permissions = role.get('permissions');
	    		var selector = '[value='+permissions.join('], .extrainputs .permissions input:checkbox[value=')+']';
	    		$checkboxes = self.container.form.$('.extrainputs .permissions input:checkbox'+selector);
	    	}
	    	
	    	if ($checkboxes){
	    		$checkboxes.each(function(){
	    			var $this = $(this);
					if (wrap){
						self.wrapColor($this, color);
						$this.prop('checked', true);
						$this.prop('disabled', true);
					}
					else{
						self.unwrapColor($this);
						$this.prop('checked', false);
						$this.prop('disabled', false);
					}
				});
			}
			
		},
		wrapColor: function($el, color){
			var $fieldset = $el.parents('fieldset:first');
			var $parent = $el.parent();
			if ($parent.is('span'))
				this.unwrapColor($el);
			$el.wrap('<span></span>');
			$fieldset.find('span').css('border', '3px solid '+color );
		},
		unwrapColor: function($el){
			var $parent = $el.parent();
			if ($parent.is('span'))
				$el.unwrap();
		}
	});
	return UsersView;
});