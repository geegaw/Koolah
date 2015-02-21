/**
 * @fileOverview defines home view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/defaultView',
  'text!templates/common/koolahBody.html',
  'text!templates/home/home.html',
], function($, _, Backbone, koolahToolkit, DefaultView, koolahBody, home){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var HomeView = Backbone.View.extend({
		defaults: {
			classname: 'home',
			title: 'Home',
			label: 'Home',
		},
		initialize: function(router, sessionUser){
			this.router = router;
			this.sessionUser = sessionUser;

			var tmpl = _.template( koolahBody );
			document.title = 'home | koolah';
			$(tmpl(this.defaults)).insertAfter('header');
			
			this.setElement('#home');
			this.render();
		},
		render: function(){
			var tmpl = _.template(home);
			this.$el.html(tmpl({ usercan: this.sessionUser.can }));
		},
		events: {
			'click .speed a': 'navigateSite',
		},
		navigateSite: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	$('body > section').remove();
	    	this.router.navigate($this.attr('href'), {trigger: true});
	    },
	});
	return HomeView;
});