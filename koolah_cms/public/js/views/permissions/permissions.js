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
  'models/roles/sitePermissions',
  'text!templates/permissions/permissionsForm.html',
], function($, _, Backbone, koolahToolkit, SitePermissions, permissionForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var PermissionsView = Backbone.View.extend({
		initialize: function(){
			this.sitePermissions = new SitePermissions();
		},
		render: function(model){
			var tmpl = _.template(permissionForm);
			
			this.$el.html(tmpl({ sitePermissions: this.sitePermissions.toJSON(), model: model.toJSON(), fn: tmpl}));
			this.$('.group .input:checkbox:not(.selectAll)').trigger('change');
		},
		events: {
			'change .selectAll'								: 'togglePermissions',
			'change .group input:checkbox:not(.selectAll)'	: 'toggleSelectAll',
		},
		togglePermissions: function(e){
			e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $group = $this.parents('.group:first');
	    	
	    	if ($this.is(':checked'))
	    		$group.find('input:checkbox:not(.selectAll)').prop('checked', true);
	    	else
	    		$group.find('input:checkbox:not(.selectAll)').prop('checked', false);
	    	$group.find('input:checkbox:not(.selectAll)').trigger('change');
		},
		toggleSelectAll: function(e){
			e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $group = $this.parents('.group:first');
	    	if ($this.is(':checked')){
	    		if (!$group.find('input:checkbox:not(.selectAll):not(:checked)').length){
	    			var self = this;
	    			$group.find('.selectAll').prop('checked', true);
	    		}
	    	}
	    	else
	    		$group.find('.selectAll').prop('checked', false);
		}
	});
	return PermissionsView;
});