/**
 * @fileOverview defines page view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/defaultView',
  'collections/pages/pages',
  'models/pages/page',
], function($, _, Backbone, koolahToolkit, DefaultView, Pages, Page){
	var PagesView = DefaultView.extend({
		collection: new Pages(),
		defaults: {
			classname: 'pages',
			title: 'Pages',
			label: 'Pages',
		},
		initialize: function(router, sessionUser, args){
			PagesView.__super__.initialize.apply(this, [router, sessionUser, args]);
			this.setElement('#pages');
			console.log('init');
			_.bindAll(this, 'edit');
			
			this.container.form.render = this.edit;
			this.router = router;
		},
		edit: function(e){
			var model = this.container.form.model;
			this.$el.remove();
			var url = '/page';
			if (model.id)
				url+= '/'+model.id;
			this.router.navigate(url, {trigger: true});
	    },
	});
	return PagesView;
});