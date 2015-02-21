/**
 * @fileOverview defines myaccount view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/koolahFormView',
  'text!templates/common/koolahBody.html',
  'text!templates/users/userForm.html',
  
], function($, _, Backbone, koolahToolkit, KoolahFormView, koolahBody, userForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var AccountView = Backbone.View.extend({
		defaults: {
			classname: 'account',
			title: 'My Account',
			label: 'My Account',
			
		},
		initialize: function(router, sessionUser, args){
			var data = this.defaults;
			var tmpl = _.template( koolahBody );
			document.title = (args.title ? args.title + ' | ': '') + 'koolah';
			$(tmpl(data)).insertAfter('header');
			this.setElement('#account');
console.log(sessionUser)			
			this.userForm = new KoolahFormView({sessionUser: sessionUser});
			this.userForm.defaults = this.defaults;
			this.userForm.model = sessionUser;
			this.userForm.inputTemplate = userForm;
			this.userForm.$appendTo = this.$el;
			this.userForm.close = this.close;
			this.userForm.render();
			this.userForm.$('.cancel').remove(); 
			
		},
		close: function(){
			//e.preventDefault();
		}
	});
	return AccountView;
});