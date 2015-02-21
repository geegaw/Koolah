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
  'views/common/defaultView',
  'views/common/koolahFormView',
  'views/taxonomy/taxonomyRelation',
  'views/uploadCenter/crop',
  'collections/uploadCenter/files',
  'models/uploadCenter/file',
  'text!templates/uploadCenter/fileForm.html',
  'text!templates/uploadCenter/list.html',
  'text!templates/uploadCenter/imageUpload.html',
  'text!templates/uploadCenter/cropForm.html',
], function($, _, Backbone, koolahToolkit, DefaultView, KoolahFormView, TaxonomyRelationView, CropView, Files, File, fileForm, list, imageUpload){
	/**
	 * RatiosView
	 * 
	 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
	 * @package koolah\cms\public\js\views\ratios
	 * @class ratios view
	 */
	var UploadCenterView = DefaultView.extend({
		collection: new Files(),
		defaults: {
			classname: 'files',
			title: 'Upload Center',
			label: 'Upload Center',
		},
		initialize: function(router, sessionUser, args){
			UploadCenterView.__super__.initialize.apply(this, [router, sessionUser, args]);
			this.setElement('#files');
			_.bindAll(this, 'renderFileFormExtras');
			_.extend(this.events, DefaultView.prototype.events);
			
			this.container.listTemplate = list;
			this.container.form.inputTemplate = fileForm; 
			this.container.form.renderCallback = this.renderFileFormExtras;
			
			this.cropForm = new CropView({sessionUser: sessionUser});
			this.cropForm.$appendTo = this.$el;
		},
		events: {
			'click .crop': 'crop',
			'change #fileInput': 'preloadFile',
		},
		crop: function(e){
			e.preventDefault();
			this.container.form.close();
	    	var $this = $(e.currentTarget);
	    	var $files = $this.parents('.files:first');
	    	var model = this.collection.get( $files.data().id );
    		this.cropForm.model = model;
	    	this.cropForm.render();
		},
		renderFileFormExtras: function (file){
			this.handleTaxonomy(file);
			this.handleImageFields(file);
		},
		handleImageFields: function(file){
			var tmpl = _.template(imageUpload);
			if (file.isImage()){
				$('#altField').show();
				this.container.form.$('.extrainputs').append(tmpl({
					src: file.get('url'),
				}));
				$('#uploadPreview').show();
			}
			else{
				this.container.form.$('.extrainputs').append(tmpl({
					src: ''
				}));
                $('#altField').hide();
			}
		},
		handleTaxonomy: function(file){
			var self = this;
			this.cropForm.close();			
			this.taxonomyRelationView = new TaxonomyRelationView();
			this.taxonomyRelationView.setElement( this.container.form.$('.extrainputs') );
			this.taxonomyRelationView.collection = this.container.form.model.get('taxonomy');
console.log(this.container.form.model);
			this.taxonomyRelationView.collection.on({
				add: function(){
					self.container.form.model.set('taxonomy', self.taxonomyRelationView.collection);
				},
				remove: function(){
					self.container.form.model.set('taxonomy', self.taxonomyRelationView.collection);
				},
			});
			this.taxonomyRelationView.render();
		},
		preloadFile: function(e){
			var self = this;
			var $this = $(e.currentTarget);
			var $form = $this.parents('.koolahForm:first');
			var id = $form.data().id;
			var file = this.collection.get(id);
			if (!file)
				file = new File();
			var oFile = document.getElementById('fileInput').files[0];
			
			$('#uploadPreview').hide();
			$('#altField').hide();
			
			var oReader = new FileReader();
	    	oReader.onload = function(e){
	    		$('#fileUploadProgress').val( 100 );
                setTimeout(function(){$('#fileUploadProgress').hide();}, 500);
                
	    		file.set({
					'ext': file.getExtFromFilename( oFile.name ),
					'size': oFile.size,
				});
				
				if (!file.isValid())
	                koolahToolkit.errorMsg( self.container.form.$('.msgBlock'), file.validationError );
		    	else{
		    		file.set('file', e.target.result);
		    		if (!file.id)
		    			self.container.form.model = file;

		    		if( file.isImage() ) {
	                    $('#uploadPreview img').attr('src', e.target.result);
	                    $('#altField').show();
	                    $('#uploadPreview').show();
	                }
		       	}
			};
	    	oReader.onprogress= function(evt){
	    	    if (evt.lengthComputable){
	                $('#fileUploadProgress').show();
	                var loaded = parseInt( (evt.loaded / evt.total) * 100 );
	                $('#fileUploadProgress').val( loaded );
	            }
		    };
			oReader.readAsDataURL(oFile);
		}
	});
	return UploadCenterView;
});