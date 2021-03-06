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
  'collections/menus/menus',
  'models/menus/menu',
  'text!templates/menus/menuForm.html',
  'text!templates/common/koolahList.html',
], function($, _, Backbone, koolahToolkit, DefaultView, KoolahContainerView, KoolahFormView, Menus, Menu, menuForm, koolahList){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var MenuFormView = KoolahFormView.extend({
		defaults: {
			classname: 'menu',
			title: 'Menu',
			label: 'Menu',
		},
		initialize: function(args){
			MenuFormView.__super__.initialize.apply(this, [args]);
			_.extend(this.events, KoolahFormView.prototype.events);
			_.bindAll(this, 'renderSubMenus', 'afterSave', 'setListActions', 'addMenu', 'updateMenus');
			if (args && args.sessionUser)
				this.sessionUser = args.sessionUser;
			
			this.extra = {
    			addSubset: true,
	    	};
	    	this.renderCallback = this.renderSubMenus;
			this.inputTemplate = menuForm; 
			this.$appendTo = $('#menus');
			if (args && args.saveCallback)
				this.saveCallback = args.saveCallback;
			else
				this.saveCallback = this.afterSave;
		},
		renderSubMenus: function(menu){
			var self = this;
			var subMenus = new Menus();
			if (menu.id){
				subMenus.parentId =  menu.id;
				subMenus.fetch().done(function(){
					self.subMenuContainer = new KoolahContainerView({sessionUser: self.sessionUser});
					self.subMenuContainer.defaults = {
						classname: 'menu',
						label: 'Sub Menu',
					};
					self.subMenuContainer.listTemplate = koolahList;
					
					self.subMenuContainer.collection = subMenus;
					self.subMenuContainer.setElement( self.$('.extra') );
					self.$('.extra').append('<div class="list"></div>');
					self.subMenuContainer.renderList({
						'$el': self.$('.extra .list'),
					});
					self.subMenuContainer.setElement( self.$('.extra') );
					self.subMenuContainer.form = new MenuFormView({sessionUser: self.sessionUser});
					self.subMenuContainer.form.parent = self;
					
					self.setListActions();
				});
			}
		},
		setListActions: function(){
			var self = this;
			if (this.sessionUser.can('menus_m')){
				this.subMenuContainer.$('.list ul').sortable({
					items: '.menu',
					receive: self.addMenu,
					tolerance: 'pointer',
					update: self.updateMenus,
					connectWith: '.list ul',
				}); 
			}
		},
		events: {
	    	'click .add'	: 'newMenu',
	    },
	    newMenu: function(e){
			var form = new MenuFormView({sessionUser: this.sessionUser});
			form.parent = this;
	    	form.model = new Menu();
	    	if (this.model.id)
	    		form.model.set({parentID: this.model.id}); 
	    	form.render();
	    },
	    afterSave: function(menu){
	    	if (this.parent){
		    	var parent = this.parent;
		    	parent.renderSubMenus(parent.model);
	    	}
	    },
	    addMenu: function(e, ui){
			var self = this;
			var $this = $(e.target);
			var $menu = $(ui.item); 
	    	var menuId = $menu.data().id;
	    	var menu = new Menu();
	    	$menu.removeClass('menus').addClass('menu');
	    	menu.id = menuId;
	    	menu.fetch().done(function(){
	    		menu.set({parentID: self.model.id});
	    		menu.save().done(function(){
		    		self.subMenuContainer.collection.add(menu);
		    	}); 
	    	});
	    	
		},
		updateMenus: function(e, ui){
			var collection = this.subMenuContainer.collection;
			this.subMenuContainer.$('.menu').each(function(i){
				var id = $(this).data().id;
				var model = collection.get(id);
				if (model)
					model.set('order', i).save();
			});
		},
	     
	});
	return MenuFormView;
});