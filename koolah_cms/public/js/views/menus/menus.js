/**
 * @fileOverview defines menu view
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
  'views/menus/menuForm',
  'collections/menus/menus',
  'models/menus/menu',
  'text!templates/menus/menuForm.html',
  'text!templates/common/koolahList.html',
], function($, _, Backbone, koolahToolkit, DefaultView, KoolahContainerView, KoolahFormView, MenuFormView, Menus, Menu, menuForm, koolahList){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var MenuView = DefaultView.extend({
		collection: new Menus(),
		defaults: {
			classname: 'menus',
			title: 'Menus',
			label: 'Menus',
			
		},
		initialize: function(router, sessionUser, args){
			MenuView.__super__.initialize.apply(this, [router, sessionUser, args]);
			_.extend(this.events, DefaultView.prototype.events);
			this.setElement('#menu');
			_.bindAll(this, 'afterSave', 'setListActions', 'addMenu', 'updateMenus');
			
			this.container.renderListCallback = this.setListActions;
			//set menu form data
			var attrs = {
				saveCallback: this.afterSave,
				sessionUser: sessionUser
			};
			this.container.form = new MenuFormView(attrs);
		},
		setListActions: function(){
			var self = this;
			if (this.sessionUser.can('menus_m')){
				this.container.$('.list ul').sortable({
					items: '.menus',
					receive: self.addMenu,
					tolerance: 'pointer',
					update: self.updateMenus,
					connectWith: '.list ul',
				}); 
			}
		},
		afterSave: function(model){
			this.container.collection.add(model);
			this.container.renderList();
		},
		addMenu: function(e, ui){
			var self = this;
			var $this = $(e.target);
			var $menu = $(ui.item); 
	    	var menuId = $menu.data().id;
	    	var menu = new Menu();
	    	$menu.removeClass('menu').addClass('menus');
	    	
	    	menu.id = menuId;
	    	menu.fetch().done(function(){
	    		menu.set({parentID: null});
	    		menu.save().done(function(){
		    		self.container.collection.add(menu);
		    	}); 
	    	});
		},
		updateMenus: function(e, ui){
			var collection = this.container.collection;
			this.container.$('.menus').each(function(i){
				var id = $(this).data().id;
				var model = collection.get(id);
				if (model)
					model.set('order', i).save();
			});
		},
	});
	return MenuView;
});