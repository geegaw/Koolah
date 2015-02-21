define([
  'jquery',
  'underscore',
  'backbone',
  'toolkit/toolkit',
  'text!templates/common/koolahForm.html',
  'text!templates/common/koolahList.html',
  'text!templates/common/confirmation.html',
], function($, _, Backbone, koolahToolkit, koolahForm, list, confirmation){
	var KoolahFormView = Backbone.View.extend({
		initialize: function(args){
			if (args && args.sessionUser)
				this.sessionUser = args.sessionUser;
			if (!this.template)
				this.template = koolahForm;
			this.extra = {};
		},
		render: function(attrs){
			this.close();
			var tmpl = _.template(this.inputTemplate);
			var inputs = tmpl({model: this.model.toJSON(), usercan: this.sessionUser.can}); 
			
			var template = this.template;
			if (attrs && attrs.template)
				template = atts.template;
			var tmpl = _.template( template );
			
			
			var label = 'New '+this.defaults.label;
			if (attrs && attrs.label)
				label = attrs.label;
			else if (this.model.id)
				label = 'Edit '+this.defaults.label;

	    	this.$appendTo.append(tmpl({
	    		label: label,
	    		classname: this.defaults.classname,
	    		model: this.model.toJSON(),
	    		inputs: inputs,
	    		extra: this.extra 
 			}));
 			
 			if (this.model.id)
 				this.setElement( $('#'+this.defaults.classname+'Form'+this.model.id) );
 			else
 				this.setElement( $('#'+this.defaults.classname+'Form') );
 			
 			if (this.renderCallback)
 				this.renderCallback(this.model);
 			this.$('.activeform').activeForm();
		},
		events: {
	    	'click .save'		: 'saveModel',
	    	'submit form'		: 'saveModel',
	    	'click  .cancel'	: 'close',
	    },
	    close: function(e){
console.log(e);	    	
			if (e) e.preventDefault();
			this.$el.remove();
			if (this.closeCallback)
				this.closeCallback();
	    },
	    saveModel: function(e){
	    	if (e) e.preventDefault();
	    	var data =  koolahToolkit.formToAssoc( this.$('form') );
	    	var self = this;

	    	var save = this.model.set(data).save();
	    	if (this.model.validationError)
	    		koolahToolkit.errorMsg( this.$('.msgBlock'), this.model.validationError);
	    	else{
	    		save.success(function(){
		    		koolahToolkit.successMsg( self.$('.msgBlock:first') );
		    		self.close();
		    		if (self.saveCallback)
 						self.saveCallback( self.model );
	    		})
	    		.error(function(response){
	    			koolahToolkit.errorMsg( self.$('.msgBlock'), response.responseJSON.msg);
	    		});
	    	}
	    },
	});

	return KoolahFormView;
});