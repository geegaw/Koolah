/**
 * @fileOverview defines users view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'collections/taxonomy/taxonomy',
  'models/taxonomy/term',
  'text!templates/taxonomy/taxonomyRelationsForm.html',
  'text!templates/taxonomy/termRelationForm.html',
  'select2',
], function($, _, Backbone, koolahToolkit, Taxonomy,Term, taxonomyRelationsForm, termRelationForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var TaxonomyRelationView = Backbone.View.extend({
		collection: new Taxonomy(),
		initialize: function(router, sessionUser, args){
			this.router = router;
			this.sessionUser = sessionUser;
			
			_.bindAll(this, 'renderTerm', 'findTerm');
		},
		render: function(){
			var tmpl = _.template(taxonomyRelationsForm);
			this.$el.html(tmpl());
			var self = this;
			
			this.$('.addTaxonomy').select2({
				placeholder: "Select a Term",
				minimumInputLength: 3,
    			allowClear: true,
    			query: self.findTerm, 
			});
			
			this.renderTerms();
		},
		renderTerms: function(){
			var self = this;
			_.each(this.collection.models, function(term){
				self.renderTerm(term);
			});
		},
		renderTerm: function(term){
			var tmpl = _.template(termRelationForm);
			this.$('.relations').append(tmpl({
				model: term.toJSON(),
			}));
		},
		events:{
			'change .addTaxonomy' 	: 'addTerm',
			'click .del'			: 'removeTerm',
		},
		findTerm: function(query){
			var q = query.term;
			var data = {results: []};
			var self = this;
			var taxonomy = new Taxonomy();
			taxonomy.fetch({data:{
    				query: {
    					label: (q.indexOf('"') >= 0) ? q.replace(/"/g, '') : '/'+q+'/i',
    				},
    			}
    		})
			.success(function(response){
				_.each(taxonomy.models, function(model){
					data.results.push({
						id: model.id,
						text: model.get('label'), 
					});
				});
				query.callback(data);
			})
			.error(function(response){
				console.log(response);
				query.callback(data);
			});
		},
		addTerm: function(e){
			var $this = $(e.currentTarget);
			var data = $this.select2('data');
			var term = new Term();
			term.set({
				id:data.id,
				label: data.text,
			});
			this.collection.add(term);
			this.renderTerm(term);
			$this.select2('data', {id:null, text:''});
		},
		removeTerm: function(e){
			e.preventDefault();
			var $this = $(e.currentTarget);
			var $pod = $this.parents('.pod:first');
			var id = $pod.data().id;
			var term = this.collection.get(id);
			this.collection.remove(term);
			$pod.remove();
		}
	});
	return TaxonomyRelationView;
});