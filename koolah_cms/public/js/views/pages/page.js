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
//  'views/common/defaultView',
  'models/pages/page',
  'text!templates/common/koolahBody.html',
  'text!templates/pages/page/templateInfo.html',
  'text!templates/pages/page/commandSection.html',
], function($, _, Backbone, koolahToolkit, /*DefaultView,*/ Page, koolahBody, templateInfo, commandSection){
	var PageView = Backbone.View.extend({
		model: new Page(),
		defaults: {
			classname: 'page',
			title: 'Page',
			label: 'Page',
		},
		initialize: function(router, sessionUser, args){
			//PageView.__super__.initialize.apply(this, [router, sessionUser, args]);
			var tmpl = _.template( koolahBody );
			document.title = this.defaults.title + ' | koolah';
			$(tmpl(this.defaults)).insertAfter('header');
			
			this.setElement('#page');
			this.model.set('id', args.id);
		},
		render: function(){
			this.renderTemplateInfo();
			this.renderCommandSection();
		},
		renderTemplateInfo: function(){
			var tmpl = _.template( templateInfo );
			this.$el.append(tmpl({}));
		},
		renderCommandSection: function(){
			var tmpl = _.template( commandSection );
			this.$el.append(tmpl({model: this.model.toJSON()}));
		},
	});
	return PageView;
});