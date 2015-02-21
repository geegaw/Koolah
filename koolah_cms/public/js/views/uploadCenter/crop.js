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
  'views/common/koolahFormView',
  'models/uploadCenter/file',
  'models/uploadCenter/crop',
  'collections/ratios/ratios',
  'text!templates/uploadCenter/cropForm.html',
  'text!templates/uploadCenter/ratioButtons.html',
  'plugins/jquery.Jcrop',
], function($, _, Backbone, koolahToolkit, KoolahFormView, File, Crop, Ratios, cropForm, ratioButtons){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var CropView = KoolahFormView.extend({
		defaults: {
			classname: 'file',
			title: 'Crop',
			label: 'Crop',
		},
		inputTemplate: cropForm, 
		initialize: function(args){
			this.model = new File();
			if (args && args.sessionUser)
				this.sessionUser = args.sessionUser;
			
			CropView.__super__.initialize.apply(this, args);
//			_.bindAll(this, 'renderFileFormExtras');
			_.extend(this.events, KoolahFormView.prototype.events);

			this.extra.extraclass = 'cropForm';
			this.ratios = new Ratios();
			this.ratios.fetch();
			
			$('head').append('<link rel="stylesheet" href="/public/css/jcrop/jquery.Jcrop.css" type="text/css" />');
		},
		events: {
			'click #ratios button'	: 'changeRatio',
			'blur #cropWidth'		: 'updateCustomHeight',
			'blur #cropHeight'	: 'updateCustomWidth',
		},
		renderCallback: function(file){
			$('#filesContainer button.open').trigger('click');
			var tmpl = _.template(ratioButtons);
console.log(this.ratios);			
			this.$('#ratios').append( tmpl({ratios:this.ratios.toJSON()}) );
			var self = this;
			$('#cropImg').Jcrop({
	                bgColor:     '#000',
	                bgOpacity:   .25,
	                setSelect:  [10, 10, 210, 210]
	            },
	            function(){
	            	self.Jcrop = this;
	                $('#cropImgHeight').css('height', $('#cropImg').height() );
	                $('#cropImgHeight span').html( $('#cropImg').height()+'px' );
	                $('#cropImgWidth').css('width', $('#cropImg').width() );
	                $('#cropImgWidth span').html( $('#cropImg').width()+'px' );
	            }
            );
		},
		closeCallback: function(){
			$('#filesContainer .closed').trigger('click');
		},
		changeRatio: function(e){
			var $this = $(e.currentTarget);
			var data = $this.data();
			$('#ratios .active').removeClass('active');
			$this.addClass('active');
			
			if (data.id == 'freeForm'){
				$('#freeFormArea input').val('');
				$('#freeFormArea').show();
				this.updateAspectRatio(null);
			}
			else{
				$('#freeFormArea').hide();
				var ratio = data.w / data.h;
				var base = 10;
				var w = 200;
				var h = 200;
				if (data.w > data.h)
					h = this.calculateHeight(w, ratio); 
				else
					w = this.calculateWidth(h, ratio);
				var coords = [base, base, (w + base), (h + base)];
				this.Jcrop.setSelect(coords);
				this.updateAspectRatio(ratio);
			}
		},
		saveModel: function(e){
			if (e) e.preventDefault();
	    	var data =  koolahToolkit.formToAssoc( this.$('form') );
	    	var self = this;
			var ratio = $('#ratios button.active').data();
			var crop = this.model.get('crops').findWhere({ratio:ratio.id});
			if (!crop)
				crop = new Crop();
			crop.set(this.Jcrop.tellSelect());
			if (ratio.id == 'freeForm'){
				var customRatio = koolahToolkit.formToAssoc( $('#freeFormArea') );
				crop.set('ratio', customRatio);
			}
			else
				crop.set('ratio', ratio.id);
			
			if (crop.isNew())
				this.model.get('crops').add(crop);
			
			console.log(this.model)
console.log(crop);			
//*
	    	var save = this.model.set(data).save();
	    	if (this.model.validationError)
	    		koolahToolkit.errorMsg( this.$('.msgBlock'), this.model.validationError);
	    	else{
	    		save.success(function(){
		    		koolahToolkit.successMsg( self.$('.msgBlock:first') );
	    		})
	    		.error(function(response){
	    			koolahToolkit.errorMsg( self.$('.msgBlock'), response.responseJSON.msg);
	    		});
	    	}
//*/
	   	},
	   	updateCustomHeight: function(e){
	   		var $this = $(e.currentTarget);
	   		var width = $this.val();
	   		
	   		var ratio = null;
	   		if (width){
		   		if ($('#cropHeight').val()){
			   		ratio = $('#cropWidth').val() / height;
			   	}
			   	else{
			   		var coords = this.Jcrop.tellSelect();
			   		ratio = coords.w / coords.h;		   		
			   		$('#cropHeight').val( this.calculateHeight(width, ratio) );
		   		}
		   	}
		   	else if ($('#cropHeight').val() ){
		   		$('#cropHeight').trigger('blur');
		   	}
	   		else
	   			this.updateAspectRatio(null);
	   	},
	   	updateCustomWidth: function(e){
	   		var $this = $(e.currentTarget);
	   		var height = $this.val();
	   		var ratio = null;
	   		if (height){
		   		if ($('#cropWidth').val()){
			   		var ratio = $('#cropWidth').val() / height;
			   	}
			   	else{
			   		var coords = this.Jcrop.tellSelect();
			   		var ratio = coords.w / coords.h;		   		
			   		$('#cropWidth').val( this.calculateWidth(height, ratio) );
			   	}
			   	this.updateAspectRatio(ratio);
	   		}
		   	else if ($('#cropWidth').val() ){
		   		$('#cropWidth').trigger('blur');
		   	}
	   		else
	   			this.updateAspectRatio(null);
	   	},
		updateAspectRatio: function(ratio){
			if (ratio){
		   		this.Jcrop.setOptions({
					aspectRatio: ratio,
				});
			}
			else{
				this.Jcrop.setOptions({
					aspectRatio: null,
				});
			}
		},
		calculateWidth: function(h, ratio){
			return parseInt(h * ratio);
		},
		calculateHeight: function(w, ratio){
			return parseInt(w / ratio);
		},
	});
	return CropView;
});