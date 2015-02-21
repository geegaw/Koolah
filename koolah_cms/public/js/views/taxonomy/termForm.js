/**
 * @fileOverview defines term view
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
  'collections/taxonomy/taxonomy',
  'models/taxonomy/term',
  'text!templates/taxonomy/termForm.html',
  'text!templates/common/koolahList.html',
], function($, _, Backbone, koolahToolkit, DefaultView, KoolahContainerView, KoolahFormView, Taxonomy, Term, termForm, koolahList){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var TermFormView = KoolahFormView.extend({
		defaults: {
			classname: 'term',
			title: 'Term',
			label: 'Term',
		},
		initialize: function(args){
			TermFormView.__super__.initialize.apply(this, [args]);
			_.extend(this.events, KoolahFormView.prototype.events);
			_.bindAll(this, 'renderSubTaxonomy', 'afterSave', 'setListActions', 'addTerm', 'updateTaxonomy');
			if (args && args.sessionUser)
				this.sessionUser = args.sessionUser;
			
			this.extra = {
    			addSubset: true,
	    	};
	    	this.renderCallback = this.renderSubTaxonomy;
			this.inputTemplate = termForm; 
			this.$appendTo = $('#taxonomy');
			if (args && args.saveCallback)
				this.saveCallback = args.saveCallback;
			else
				this.saveCallback = this.afterSave;
		},
		renderSubTaxonomy: function(term){
			var self = this;
			var subTaxonomy = new Taxonomy();
			if (term.id){
				subTaxonomy.parentId =  term.id;
				subTaxonomy.fetch().done(function(){
					self.subTermContainer = new KoolahContainerView({sessionUser: self.sessionUser});
					self.subTermContainer.defaults = {
						classname: 'term',
						label: 'Sub Term',
					};
					self.subTermContainer.listTemplate = koolahList;
					
					self.subTermContainer.collection = subTaxonomy;
					self.subTermContainer.setElement( self.$('.extra') );
					self.$('.extra').append('<div class="list"></div>');
					self.subTermContainer.renderList({
						'$el': self.$('.extra .list'),
					});
					self.subTermContainer.setElement( self.$('.extra') );
					self.subTermContainer.form = new TermFormView({sessionUser: self.sessionUser});
					self.subTermContainer.form.parent = self;
					
					self.setListActions();
				});
			}
		},
		setListActions: function(){
			var self = this;
			if (this.sessionUser.can('taxonomy_m')){
				this.subTermContainer.$('.list ul').sortable({
					items: '.term',
					receive: self.addTerm,
					tolerance: 'pointer',
					update: self.updateTaxonomy,
					connectWith: '.list ul',
				}); 
			}
		},
		events: {
	    	'click .add'	: 'newTerm',
	    },
	    newTerm: function(e){
			var form = new TermFormView({sessionUser: this.sessionUser});
			form.parent = this;
	    	form.model = new Term();
	    	if (this.model.id)
	    		form.model.set({parentID: this.model.id}); 
	    	form.render();
	    },
	    afterSave: function(term){
	    	if (this.parent){
		    	var parent = this.parent;
		    	parent.renderSubTaxonomy(parent.model);
	    	}
	    },
	    addTerm: function(e, ui){
			var self = this;
			var $this = $(e.target);
			var $term = $(ui.item); 
	    	var termId = $term.data().id;
	    	var term = new Term();
	    	$term.removeClass('taxonomy').addClass('term');
	    	term.id = termId;
	    	term.fetch().done(function(){
	    		term.set({parentID: self.model.id});
	    		term.save().done(function(){
		    		self.subTermContainer.collection.add(term);
		    	}); 
	    	});
		},
		updateTaxonomy: function(e, ui){
			var collection = this.subTermContainer.collection;
			this.subTermContainer.$('.term').each(function(i){
				var id = $(this).data().id;
				var model = collection.get(id);
				if (model)
					model.set('order', i).save();
			});
		},
	     
	});
	return TermFormView;
});