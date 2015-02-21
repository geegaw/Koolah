/**
 * @fileOverview defines taxonomy view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/defaultView',
  'views/taxonomy/termForm',
  'collections/taxonomy/taxonomy',
  'models/taxonomy/term',
  'text!templates/taxonomy/termForm.html',
], function($, _, Backbone, koolahToolkit, DefaultView, TermFormView, Taxonomy, Term, termForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var TaxonomyView = DefaultView.extend({
		collection: new Taxonomy(),
		defaults: {
			classname: 'taxonomy',
			title: 'Taxonomy',
			label: 'Taxonomy',
			
		},
		initialize: function(router, sessionUser, args){
			TaxonomyView.__super__.initialize.apply(this, [router, sessionUser, args]);
			_.extend(this.events, DefaultView.prototype.events);
			this.setElement('#taxonomy');
			_.bindAll(this, 'afterSave', 'setListActions', 'addTerm', 'updateTaxonomy');
			
			this.container.renderListCallback = this.setListActions;
			//set taxonomy form data
			var attrs = {
				saveCallback: this.afterSave,
				sessionUser: sessionUser,
			};
			this.container.form = new TermFormView(attrs);
		},
		setListActions: function(){
			var self = this;
			if (this.sessionUser.can('taxonomys_m')){
				this.container.$('.list ul').sortable({
					items: '.taxonomy',
					receive: self.addTerm,
					tolerance: 'pointer',
					update: self.updateTaxonomy,
					connectWith: '.list ul',
				}); 
			}
		},
		afterSave: function(model){
			this.container.collection.add(model);
			this.container.renderList();
		},
		addTerm: function(e, ui){
			var self = this;
			var $this = $(e.target);
			var $term = $(ui.item); 
	    	var termId = $term.data().id;
	    	var term = new Term();
	    	$term.removeClass('term').addClass('taxonomy');
	    	
	    	term.id = termId;
	    	term.fetch().done(function(){
	    		term.set({parentID: null});
	    		term.save().done(function(){
		    		self.container.collection.add(term);
		    	}); 
	    	});
		},
		updateTaxonomy: function(e, ui){
			var collection = this.container.collection;
			this.container.$('.taxonomy').each(function(i){
				var id = $(this).data().id;
				var model = collection.get(id);
				if (model)
					model.set('order', i).save();
			});
		},
	});
	return TaxonomyView;
});