/**
 * @fileOverview defines ratio view
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
  'collections/ratios/ratios',
  'models/ratios/ratio',
  'models/ratios/ratioSize',
  'text!templates/ratios/ratioEditForm.html',
  'text!templates/ratios/ratioSizesList.html',
  'text!templates/ratios/ratioSizeForm.html',
], function($, _, Backbone, koolahToolkit, DefaultView, KoolahContainerView, KoolahFormView, Ratios, Ratio, RatioSize, ratioEditForm, ratioSizesList, ratioSizeForm){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var RatiosView = DefaultView.extend({
		collection: new Ratios(),
		defaults: {
			classname: 'ratios',
			label: 'Ratios',
			title: 'Ratios',
		},
		initialize: function(router, sessionUser, args){
			RatiosView.__super__.initialize.apply(this, [router, sessionUser, args]);
			this.setElement('#ratios');
			_.bindAll(this, 'saveRatioSize', 'renderRatioSizes', 'delRatioSize');
			
			//set ratio form data
			this.container.form.inputTemplate = ratioEditForm; 
			this.container.form.renderCallback = this.renderRatioSizes;
			this.container.form.closeCallback = function(){ $('#ratioSizeForm').remove(); };
			this.container.form.extra = {
    			addSubset: true,
	    	};
	    	
	    	//bind ratio size list to container view
	    	this.ratioSizeContainer = new KoolahContainerView();
			this.ratioSizeContainer.defaults = {
				classname: 'ratio',
				label: 'Ratio',
			};
			this.ratioSizeContainer.listTemplate = ratioSizesList;
			this.ratioSizeContainer.deleteModel = this.delRatioSize;
			
			//set ratio size form data
			this.ratioSizeContainer.form.$appendTo = this.$el;
			this.ratioSizeContainer.form.inputTemplate = ratioSizeForm; 
	    	this.ratioSizeContainer.form.defaults = {
	    		classname: 'ratioSize',
	    		label: 'Ratio Size',
	    	};
	    	this.ratioSizeContainer.form.saveModel = this.saveRatioSize;
		},
		renderRatioSizes: function(ratio){
			this.ratioSizeContainer.collection = ratio.get('sizes');;
			this.ratioSizeContainer.setElement( this.container.form.$('.extra') );
			this.ratioSizeContainer.renderList({
				'$el': this.container.form.$('.extra'),
			});
	    },
	    events: {
	    	'click  #ratiosForm .add'					: 'newRatioSize',
	    	'blur   #ratioSizeForm .int'				: 'maintainRatio',
	   	},
		newRatioSize: function(e){
	    	if (this.validateRatioForm()){
	    		this.ratioSizeContainer.form.model = new RatioSize(); 
	    		this.ratioSizeContainer.form.render();
	    	}
	    },
	    delRatioSize: function(e){
	    	e.preventDefault();
	    	var $this = $(e.currentTarget);
	    	
	    	var data = $this.data();
	    	var ratio = this.collection.get( $('#ratiosForm').data().id );
	    	var self = this;
	    	ratio.get('sizes').remove( data.cid );
	    	ratio.save()
	    		.success(function(){
		    		koolahToolkit.successMsg( self.container.form.$('.msgBlock') );
		    		$('#confirmation').remove();
		    		self.container.form.render();
	    		})
	    		.error(function(response){
	    			koolahToolkit.errorMsg( $('#ratioSizeForm .msgBlock'), response.responseJSON.msg);
	    		});
	    },
	    saveRatioSize: function(e){
	    	if (e) e.preventDefault();
	    	var ratioId = this.container.form.$el.data().id;
	    	var ratio = ratioId ? this.collection.get(ratioId) : new Ratio();
	    	
	    	var sizeId = this.ratioSizeContainer.form.$el.data().id;
	    	var size = (ratioId && ratio.get('sizes').get( sizeId )) ? ratio.get('sizes').get( sizeId ) : new RatioSize();
	    	var data =  koolahToolkit.formToAssoc( $('#ratioSizeForm form') );
	    	size.set(data);
	    	
	    	this.maintainRatio();
	    	if (size.isValid()){
	    		var self = this;
	    		
	    		if (!ratioId){
	    			data = koolahToolkit.formToAssoc( this.container.form.$('form') );
	    			ratio.set(data);
	    		}
	    		
	    		if (!ratio.get('sizes').get( sizeId ))
	    			ratio.get('sizes').add(size);
	    		
	    		ratio.save()
		    		.success(function(){
			    		self.ratioSizeContainer.form.close();
			    		
			    		if (!ratioId){
			    			self.collection.add(ratio);
			    			self.container.renderList();
			    		}
			    		
			    		self.container.form.model = ratio;
			    		self.container.form.render();
			    		koolahToolkit.successMsg( self.container.form.$('.msgBlock') );
		    		})
		    		.error(function(response){
		    			koolahToolkit.errorMsg( $('#ratioSizeForm .msgBlock'), response.responseJSON.msg);
		    		});
	    	}
	    	else{
	    		koolahToolkit.errorMsg( $('#ratioSizeForm .msgBlock'), size.validationError);
	    	}
	    },
	    maintainRatio : function(e){
	    	if (ratio = this.validateRatioForm()){
	    		var rtio = ratio.computeRatio();
	    		if (e){
		    		var $this = $(e.currentTarget);
		    		if ($this.attr('name') == 'w' && $this.val())
		    			$('#ratioSizeHeight').val( parseInt( new RatioSize().computeHFromRatio(rtio, $this.val())) );
		    		else if ($this.attr('name') == 'h' && $this.val())
		    			$('#ratioSizeWidth').val( parseInt( new RatioSize().computeWFromRatio(rtio, $this.val())) );
		    	}
		    	else{
		    		if ( $('#ratioSizeWidth').val() )
		    			$('#ratioSizeHeight').val( parseInt( new RatioSize().computeHFromRatio(rtio, $('#ratioSizeWidth').val())) );
		    		else if ( $('#ratioSizeHeight').val() )
		    			$('#ratioSizeWidth').val( parseInt( new RatioSize().computeWFromRatio(rtio, $('#ratioSizeHeight').val())) );
		    	}
	    	}
	    },
	    validateRatioForm: function(){
	    	var id = $('#ratiosForm').data().id;
	    	var ratio = new Ratio();
	    	var data =  koolahToolkit.formToAssoc( $('#ratiosForm form') );
	    	ratio.set(data);
	    	if (ratio.isValid())
	    		return ratio;
	    	else{
	    		koolahToolkit.errorMsg( $('#ratiosForm .msgBlock'), ratio.validationError);
	    		$('#ratiosForm .msgBlock ul').prepend('you must complete the ratio form before adding sizes');
	    		return false;
	    	}
	    },
	});
	
	return RatiosView;
});