/**
 * @fileOverview defines template view
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'views/common/koolahFormView',
  'collections/templates/templates',
  'models/templates/template',
  'models/templates/section',
  'models/templates/field',
  'text!templates/common/koolahBody.html',
  'text!templates/templates/template/template.html',
  'text!templates/templates/template/fieldForm.html',
  'text!templates/templates/template/sectionForm.html',
], function($, _, Backbone, koolahToolkit, KoolahFormView, Templates, Template, Section, Field, koolahBody, template, fieldForm, sectionForm){
	var TemplateView = Backbone.View.extend({
		model: new Template(),
		defaults: {
			classname: 'template',
			title: 'Template',
			label: 'Template',
		},
		initialize: function(router, sessionUser, data){
			//TemplateView.__super__.initialize.apply(this, [router, sessionUser, args]);
			var tmpl = _.template( koolahBody );
			document.title = this.defaults.title + ' | koolah';
			$(tmpl(this.defaults)).insertAfter('header');
			
			_.bindAll(this, 'saveSection', 'saveField', 'updateFields', 'moveField', 'updateSections');
			
			this.setElement('#template');
			if (data && data.id)
				this.model.set('id', data.id);
			else if (data && data.type)
				this.model.set('templateType', data.type);
			else
				throw new exception('Illegal Template');
				
			this.sectionForm = new KoolahFormView();
			this.sectionForm.model = new Section();
			this.sectionForm.$appendTo = this.$el;
			this.sectionForm.inputTemplate = sectionForm;
			this.sectionForm.defaults = {
				label: 'Section Name',
				classname: 'templateSection',
			};
			this.sectionForm.saveModel = this.saveSection;
			
			this.fieldForm = new KoolahFormView();
			this.fieldForm.model = new Field();
			this.fieldForm.$appendTo = this.$el;
			this.fieldForm.inputTemplate = fieldForm;
			this.fieldForm.defaults = {
				label: 'Field Name',
				classname: 'templateField',
			};
			this.fieldForm.saveModel = this.saveField;
			
			if (data && data.type){
				this.reinitialize();
				this.render();
			}
			this.router = router;
		},
		reinitialize: function(){
			this.model.set('templateDisplay', koolahToolkit.ucFirst( this.model.get('templateType') ));
			this.renderTabs();
			if (this.model.isNew()){
				var section = new Section();
				section.set({name: 'General'});
				this.model.addSection(section);
				this.renderTabLabel(section);
			}
			this.renderTab( this.model.get('sections').at(0) );
		},
		render: function(){
			var tmpl = _.template( template );
			this.$el.append(tmpl({model: this.model.toJSON()}));
			$('#nameField').activeForm();
		},
		renderFieldForm: function(field){
			this.sectionForm.close();
			this.fieldForm.model = field;
			this.fieldForm.model.set({fieldTypes: FIELD_TYPES});
			this.fieldForm.render();
		},
		renderSectionNameForm: function(){
			this.fieldForm.close();
			this.sectionForm.render();
		},
		renderTabs: function(){
			var sections = this.model.get('sections');
			var self = this;
			if (sections){
				_.each(sections.models, function(section){
					self.renderTabLabel(section);
				});
			}
		},
		sectionActions: function(){
			var self = this;
			this.$('.tabLabels').sortable({
				items: '.tab:not(#addSection)',
				update: self.updateSections
			});
			
			this.$('.tab:not(#addSection)').sortable({
				items: '#none',
				receive: self.moveField,
				tolerance: 'pointer',
			}); 
		},
		renderTab: function(tab){
			if (tab){
				var fields = tab.get('fields');
				if (fields)
					this.fillTab(fields);
			}
		},
		renderTabLabel: function(tab, callback){
			if (tab){
				var self = this;
				require(['text!templates/common/tab.html'], function(htmlTemplate){
					var tmpl = _.template( htmlTemplate );
					var model = tab.toJSON();
					model.id = tab.cid;
					$(tmpl({model: model})).insertBefore( $('#addSection') );
					if (!self.$('.tab.active').length)
						self.$('.tab:first').addClass('active');
					self.refreshTabStyles();
					self.sectionActions();
					
					if (callback)
						callback();
				});
			}
		},
		fieldActions: function(){
			var self = this;
			this.$('.fields').sortable({
				update: self.updateFields,
				connectWith: '.tab:not(#addSection)'
			});
		},
		fillTab: function(fields){
			var self = this;
			this.$('.fields').html('');
			require(['text!templates/templates/fields/display/field.html'], function(htmlTemplate){
				var tmpl = _.template( htmlTemplate );
				_.each(fields.models, function(field){
					var model = field.toJSON();
					model.id = field.cid;
					this.$('.fields').append(tmpl({model: model}));
					self.fieldActions();
	   			});
			});
		},
		events: {
			'blur 	#nameField input'			: 'changeTemplateName',
	    	'click  .tab:not(#addSection) a'	: 'changeTabs',
	    	'click  #addSection a'				: 'newSection',
	    	'click  .sectionCommands .del'		: 'delSection',
	    	'click  .sectionCommands .add'		: 'newField',
	    	'click  .fieldInfoCommands .edit' 	: 'editField',
	    	'click  .fieldInfoCommands .del' 	: 'delField',
	    	'change #fieldType'					: 'showTypeOptions',
	    	'click  #templateCommands .reset' 	: 'reset',
	    	'click  #templateCommands .save' 	: 'save',
	    	 		
	   	},
	   	changeTemplateName: function(e){
	   		if (e) e.preventDefault();
	   		var $this = $(e.currentTarget);
	   		this.model.set({label: $this.val() });
	   	},
	   	changeTabs: function(e){
	   		if (e) e.preventDefault();
	   		this.sectionForm.close();
			this.fieldForm.close();
			
	    	var $this = $(e.currentTarget);
	    	var $tab = $this.parents('.tab:first');
	    	var id = $this.data().id;
	    	var sections = this.model.get('sections');
	    	if (sections){
	    		var section = sections.get(id);
	    		this.renderTab(section);
	    		this.$('.tab.active').removeClass('active');
	    		$tab.addClass('active');
	    		this.refreshTabStyles();
	    	}
	   	},
	   	newField: function(e){
	   		e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	this.renderFieldForm(new Field());
	   	},
	   	editField: function(e){
	   		e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $field = $this.parents('.field:first');
	    	var fieldId = $field.data().id;
	    	
	    	var $section = this.$('.tab.active a');
	    	var sectionId = $section.data().id;
	    	
	    	var field =  this.model.getField(sectionId, fieldId);
	    	this.renderFieldForm(field);
	   	},
	   	delField: function(e){
	   		e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $field = $this.parents('.field:first');
	    	var fieldId = $field.data().id;
	    	var $section = this.$('.tab.active a');
	    	var sectionId = $section.data().id;
	    	
	    	var field = this.model.getField(sectionId, fieldId);
	    	this.model.removeField(sectionId, field);
	    	$field.remove();
	   	},
	   	saveField: function(e){
	   		e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var data =  koolahToolkit.formToAssoc( this.fieldForm.$('form') );
	    	this.fieldForm.model.set(data);
	    	if (this.fieldForm.model.isValid()){
	   			var $section = this.$('.tab.active a');
    			var sectionId = $section.data().id;
	    		var section = this.model.getSection(sectionId);
	    			
	    		if (this.fieldForm.model.isNew()){
	   				this.model.addField( sectionId, this.fieldForm.model );
	   			}
	   			this.renderTab(section);
	   			this.fieldForm.close();
	   		}
	   		else
	    		koolahToolkit.errorMsg( this.fieldForm.$('.msgBlock'), this.fieldForm.model.validationError);
	   	},
	   	updateFields: function(e, ui){
	    	var $this = $(e.currentTarget);
	    	var $section = this.$('.tab.active a');
			var sectionId = $section.data().id;
    		var section = this.model.getSection(sectionId);
    		var fields = section.get('fields');
	    	this.$('.field').each(function(i){
	    		var id = $(this).data().id;
	    		var field = fields.get(id);
	    		field.set('order', i); 
	    	});	
	    	
	    	fields.sort();
	    },
	    moveField: function(e, ui){
	    	var $this = $(e.target);
	    	var $field = $(ui.item); 
	    	var fieldId = $field.data().id;
	    	
	    	var $sectionFrom = this.$('.tab.active a');
			var sectionFromId = $sectionFrom.data().id;
    		var sectionFrom = this.model.getSection(sectionFromId);
    		var field = sectionFrom.getField(fieldId);
    		
    		var sectionToId = $this.find('a').data().id;
    		var sectionTo = this.model.getSection(sectionToId);
    		
    		sectionTo.get('fields').add(field);
    		sectionFrom.get('fields').remove(field);
    		
    		$field.remove();
	    },
	   	newSection: function(e){
	   		e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	this.renderSectionNameForm();
	   	},
	   	delSection: function(e){
	   		e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	var $section = this.$('.tab.active a');
			var sectionId = $section.data().id;
    		var section = this.model.getSection(sectionId);
    		this.model.removeSection(section);
    		
    		this.$('.tab.active').remove();
    		if (this.$('.tab').length == 1){
    			var section = new Section();
				section.set({name: 'General'});
				this.model.addSection(section);
				var self = this;
				this.renderTabLabel(section,function(){
					self.$('.tab:first a').trigger('click');
				});
    		}
    		else
    			this.$('.tab:first a').trigger('click');
	   	},
	   	saveSection: function(e){
	   		e.preventDefault();
	   		var $this = $(e.currentTarget);
	   		var data =  koolahToolkit.formToAssoc( this.sectionForm.$('form') );
	   		this.sectionForm.model.set(data);
	   		if (this.sectionForm.model.isValid()){
	   			if (this.sectionForm.model.isNew())
	   				this.model.addSection( this.sectionForm.model );
	   			this.renderTabLabel(this.sectionForm.model);
	   			this.sectionForm.close();
	   		}
	   		else
	    		koolahToolkit.errorMsg( this.sectionForm.$('.msgBlock'), this.sectionForm.model.validationError);
	   	},
	   	updateSections: function(e, ui){
	   		var $this = $(e.currentTarget);
	   		var sections = this.model.get('sections');
	    	this.$('.tab:not(#addSection)').each(function(i){
	    		var id = $(this).find('a').data().id;
	    		var section = sections.get(id);
	    		section.set('order', i); 
	    	});	
	    	
	    	sections.sort();
	    	this.refreshTabStyles();
	   	},
	   	reset: function(e){
	   		e.preventDefault();
	   		location.href = location.href;
	   	},
	   	save: function(e){
	   		e.preventDefault();
	   		var $this = $(e.currentTarget);
	   		koolahToolkit.resetMsg( this.$('.koolahBasicContainer .msgBlock') );
	   		
	   		if (this.fieldForm.$el.is(':visible'))
	   			koolahToolkit.errorMsg( this.$('.koolahBasicContainer .msgBlock'), "please finish making changes to your field first");
	   		else if (this.sectionForm.$el.is(':visible'))
	   			koolahToolkit.errorMsg( this.$('.koolahBasicContainer .msgBlock'), "please finish making changes to your section first");
	   		else if (this.model.isValid()){
	   			var self = this;
	   			var newModel = self.model.isNew();
	   					
	   			this.model.save()
	   				.success(function(){
	   					if (newModel)
	   						self.router.navigate('/template/'+self.model.id, {trigger: true});
	   					else
	   						koolahToolkit.successMsg( self.$('.koolahBasicContainer .msgBlock') );
	   				})
	   				.error(function(e){
	   					koolahToolkit.errorMsg( self.$('.koolahBasicContainer .msgBlock'), e);
	   				});
	   		}
	   		else
	   			koolahToolkit.errorMsg( this.$('.koolahBasicContainer .msgBlock'), this.model.validationError);
	   	},
	   	showTypeOptions: function(e){
	   		var self = this;
	   		var $this = $(e.currentTarget);
	   		var type = $this.val();
	   		var filename = '';
	   		
	   		self.fieldForm.$('.typeOptions').remove();
	   		
	   		var customFields = null;
	   		switch (type){
	   			case 'dropdown':
	   				filename = 'dropdown';
	   				break;
	   			case 'file':
	   				filename = 'file';
	   				break;
	   			case 'custom':
	   				if (!self.fieldForm.model.get('customFields')){
	   					self.fieldForm.model.set({customFields : new Templates()});
	   					self.fieldForm.model.get('customFields').templateType = 'field';
	   					var fetching = self.fieldForm.model.get('customFields').fetch();
	   				}
	   				filename = 'custom';
	   				break;
	   		}
	   		
	   		if (filename){
	   			require(['text!templates/templates/fields/form/'+filename+'.html'], function(htmlTemplate){
	   				if (fetching){
   						fetching.done(function(){
   							self.fieldForm.model.set({customFields: self.fieldForm.model.get('customFields').toJSON()});
   							var tmpl = _.template( htmlTemplate );
	   						self.fieldForm.$('.inputs').append( tmpl({model: self.fieldForm.model.toJSON()}) );
   						});
	   				}
	   				else{
	   					var tmpl = _.template( htmlTemplate );
	   					self.fieldForm.$('.inputs').append( tmpl({model: self.fieldForm.model.toJSON()}) );
	   				}
	   			}); 
	   		}
	   	},
	   	refreshTabStyles: function(){
	   		var numTabs = this.model.get('sections').length;
	   		var left = 0;
	   		var zindex = numTabs;
        	this.$('.tab').each(function(){
	            if ( $(this).hasClass('active') ){
	                $(this).css({
	                    'left': left+'px',
	                    'z-index': (numTabs + 1) 
	                });
	            }
	            else{
	                $(this).css({
	                    'left': left+'px',
	                    'z-index': zindex 
	                });
	            }
	            left-=4;
	            zindex--;
	       	});
	   	},
	   	
	});
	return TemplateView;
});