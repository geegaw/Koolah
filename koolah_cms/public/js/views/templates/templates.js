/**
 * @fileOverview defines template view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/defaultView',
  'views/common/koolahContainerView',
  'views/common/koolahFormView',
  'collections/templates/templates',
  'models/templates/template',
], function($, _, Backbone, koolahToolkit, DefaultView, KoolahContainerView, KoolahFormView, Templates, Template){
	/**
	 * TemplatesView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\templates
	 * @class templates view
	 */
	var TemplatesView = DefaultView.extend({
		collection: new Templates(),
		defaults: {
			classname: 'templates',
			label: 'Templates',
			title: 'Templates',
		},
		initialize: function(router, sessionUser, args){
			this.collection.templateType = args.type;
			TemplatesView.__super__.initialize.apply(this, [router, sessionUser, args]);
			this.setElement('#templates');
			
			_.bindAll(this, 'edit');
			
			this.container.newModel = this.edit;
			this.container.form.render = this.edit;
			this.router = router;
		},
		edit: function(e){
			this.$el.remove();
			var model = this.container.form.model;
			var url = '/template/';
			if (model && model.id)
				url+= model.id;
			else
				url = '/new'+url+this.collection.templateType;
			this.router.navigate(url, {trigger: true});
	    }	    
});
	
	return TemplatesView;
});