define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/koolahFormView',
  'views/common/paginationView',
  'text!templates/common/koolahContainer.html',
  'text!templates/common/koolahList.html',
  'text!templates/common/confirmation.html',
], function($, _, Backbone, koolahToolkit, KoolahFormView, PaginationView, koolahContainer, koolahList, confirmation){
	var KoolahContainerView = Backbone.View.extend({
		initialize: function(args){
			if (args && args.sessionUser)
				this.sessionUser = args.sessionUser;
			if (!this.form)
				this.form = new KoolahFormView(args);
			_.bindAll(this, 'saveModel');
			this.form.saveCallback = this.saveModel;
			
		},
		render: function(attrs){
			var tmpl = _.template( koolahContainer );
			var data = {
				classname: this.defaults.classname,
				label: this.defaults.label,
				usercan: this.sessionUser.can
			};
			if (attrs && attrs.classname)
				data.classname = attrs.classname;
			if (attrs && attrs.label)
				data.label = attrs.label;

			this.$appendTo.append( tmpl(data) );
			this.$('.activeform').activeForm();			
			this.setElement( this.$appendTo.find('.koolahBasicContainer') );

			if (!this.listTemplate)
				this.listTemplate = koolahList;
			if (this.renderCallback)
				this.renderCallback();
		},
		renderList: function(attrs){

			var listTemplate = this.listTemplate;
			if (attrs && attrs.listTemplate)
				listTemplate = attrs.listTemplate;

			var tmpl = _.template( listTemplate );
			
			var classname = this.defaults.classname;
			if (attrs && attrs.classname)
				classname = attrs.classname;
			
			var $el = this.$('.list');
			if (attrs && attrs.$el)
				$el = attrs.$el;

			$el.html(tmpl({
 				collection: this.collection.toJSON(),
 				classname: classname, 
 				usercan: this.sessionUser.can
 			}));
 			
 			/*
 			if (this.collection.numPages() > 1){
				this.renderPagination({
					numPages: this.collection.numPages(),
					active: page,
				});
			}
			*/
			if (this.renderListCallback)
				this.renderListCallback();
		},
		renderPagination: function(attrs){
			var paginationView = new PaginationView({
				el: this.$('.pagination'),
				collection: this.collection,
			});
			paginationView.render(attrs);
		},
		renderConfirmationMessage: function(attrs){
			attrs.msg = attrs.msg || 'Are you sure?';
			attrs.yes = attrs.yes || 'YES';
			attrs.no = attrs.no || 'NO';
			attrs.selector = attrs.selector || this.$el;
			
			var tmpl = _.template( confirmation );
			attrs.selector.append( tmpl(attrs) );
		},
		renderDeleteConfirmationMessage: function(attrs){
			this.onDeckForDelete = null;
			if (attrs.model){
				this.onDeckForDelete = attrs.model;
				attrs.toDelete = attrs.toDelete || attrs.model.get('label');
				attrs.msg = attrs.msg || 'Are you sure you want to delete '+attrs.toDelete+'?';
				attrs.yes = attrs.yes || 'YES Delete';
				attrs.no = attrs.no || 'NO Don\'t Delete';
				this.renderConfirmationMessage(attrs);
			}
		},
		events: {
	    	'click .add'				: 'newModel',
	    	'click .edit'				: 'editModel',
	    	'click .del'				: 'confirmDelModel',
	    	'click .confirmation .yes'	: 'deleteModel',
	    	'click .pagination button'	: 'paginateCollection',
	    	'click .searchGo'			: 'searchForModel',
	    	'submit .filterArea'		: 'searchForModel',
	    	'keyup .filterInput .query'	: 'searchForModel',
	    },
	    newModel: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	this.form.model = new this.collection.model();
	    	this.form.render();
	    },
	    editModel: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $el = $this.parents('li:first');
	    	var data = $el.data();
	    	this.form.model = this.collection.get( data.id );
	    	this.form.render();
	    },
	    saveModel: function(savedModel){
	    	var model = this.collection.get( savedModel.id );
	    	if( !model )
	    		this.collection.add( savedModel );
	    	this.renderList();
	    },
	    confirmDelModel: function(e){
	    	var $this = $(e.currentTarget);
	    	var data = $this.parents('li:first').data();
	    	var model = this.collection.get(data.id);
	    	if (!model)
	    		koolahToolkit.errorMsg( this.$('.msgBlock'), model.defaults.displayLabel + ' not found');
	    	else{
		    	this.renderDeleteConfirmationMessage({
		    		model: model,
		    		selector: $this.parent(),
		    	});
	    	}
	    },
	    deleteModel: function(e){
	    	e.preventDefault();
	    	var model = this.onDeckForDelete;
	    	if (model && model.id){
	    		var id = model.id;
	    		var self = this;
	    		model.destroy()
	    			.success(function(){
	    				koolahToolkit.successMsg( self.$('.msgBlock:first') );
	    				self.collection.remove(model);
			    		self.renderList();
			    		$('#confirmation').remove();
			    	})
			    	.error(function(response){
			    		koolahToolkit.errorMsg( self.$('.msgBlock:first'), response.responseJSON.msg);
					});
	    	}
	    },
	    paginateCollection: function(e){
	    	var $this = $(e.currentTarget);
	    	var page = $this.data().page;
	    	this.render( page );
	    },
	    searchForModel: function(e){
	    	if (e) e.preventDefault();
	    	var q = this.$('.filterInput .query').val();
	    	if (!q.length){
	    		this.collection.reset();
	    		this.renderList(this.currentPage);
	    	}
	    	else if (q.length >= 3){
	    		var self = this;
	    		self.collection.reset();
	    		self.currentPage = 0;
	    		self.collection.fetch({data:{
	    				query: {
	    					label: (q.indexOf('"') >= 0) ? q.replace(/"/g, '') : '/'+q+'/i',
	    				},
	    			}
	    		})
					.success(function(response){
						self.renderList();
					})
					.error(function(response){
						koolahToolkit.errorMsg( self.$('.msgBlock'), response.responseJSON.msg);
					});
	    	}
	    },
	});

	return KoolahContainerView;
});