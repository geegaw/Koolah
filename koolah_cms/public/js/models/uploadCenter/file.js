define([
  'underscore',
  'backbone',
  'models/core/node',
  'collections/taxonomy/taxonomy',
  'collections/uploadCenter/crops',
], function(_,Backbone, Node, Taxonomy, Crops){
  var File = Node.extend({
	    defaults: {
		    childClass: 'KoolahFile',
		    displayLabel: 'File',
	    },
	    initialize: function(){
	    	this.set('url',  FM_URL+'?id='+this.id);
	    	
	    	if (!this.get('taxonomy')){
	    		var taxonomy = new Taxonomy();
	    		this.set('taxonomy', taxonomy);
	    	}
	    	
	    	if (!this.get('crops')){
	    		var crops = new Crops();
	    		this.set('crops', crops);
	    	}
	    },
	    
	    parse: function(data, options){
	    	File.__super__.parse.apply(this, arguments);
console.log(this);	    	
	    	if (data.ext){
	    		this.set('ext', data.ext);
	    		this.set('type', this.getType());
	    		delete(data.ext);
	    	}
	    	
	    	var taxonomy = new Taxonomy();
	    	this.set('taxonomy', taxonomy);
	    	if (data.taxonomy)
	    		this.get('taxonomy').set( data.taxonomy );
	    	delete(data.taxonomy);
	    	
	    	var crops = new Crops();
	    	this.set('crops', crops);
	    	if (data.crops)
	    		this.get('crops').set(data.crops);
	    	delete(data.crops);
	    	
	    	return data;
	    },
		validate: function(attrs, options){
			var errors = [];
			if (!this.get('label'))
				errors.push('name is required');
			if (!this.isValidType())
				errors.push(this.get('ext')+' is not a valid file type');
			if (this.get('size') && !this.isValidSize())
				errors.push('file too large');
			if (errors.length)
				return '<ul><li>'+errors.join( '</li><li>' )+'</li></ul>';
		},
	    isImage: function(ext){  
	        if (!ext)
	            ext = this.get('ext');
	        return (_.indexOf( VALID_IMAGES, ext ) >= 0); 
	    },
	    isDoc: function(ext){  
	        if (!ext)
	            ext = this.get('ext');
	        return (_.indexOf( VALID_DOCS, ext ) >= 0);
	    },
	    isVid: function(ext){  
	        if (!ext)
	            ext = this.get('ext');
	        return (_.indexOf( VALID_VIDS, ext ) >= 0); 
	    },
	    isAudio: function(ext){  
	        if (!ext)
	            ext = this.get('ext');
	        return (_.indexOf( VALID_AUDIO, ext ) >= 0); 
	    },
	    isValidType: function( ext ){  
	        if (!ext)
	            ext = this.get('ext');
			return (_.indexOf( VALID_FILES, ext ) >= 0); 
	    },
	    isValidSize: function( size ){ 
	    	if (!size)
	            size = this.get('size');
			return size <=  MAX_FILE_SIZE;
	    },
	    isValid: function( ext, size ){ 
	    	return this.isValidType(ext) && this.isValidSize(size); 
	    },
	    getType: function(ext){
	        if (!ext)
	            ext = this.get('ext');
	        if (this.isImage(ext))
	            return 'img';
	        if (this.isDoc(ext))
	            return 'doc';
	        if (this.isVid(ext))
	            return 'vid';
	        if (this.isAudio(ext))
	            return 'aud';
	        return '';
	    },
	    getExtFromFilename: function (filename){
	        ext = filename.split('.');
	        return ext[ (ext.length -1) ].toLowerCase(); 
	    },
	    
		
	});
  
	return File;
});