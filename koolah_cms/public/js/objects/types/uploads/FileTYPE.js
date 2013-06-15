function FileTYPE( $msgBlock ) {
    this.parent = new Node( 'KoolahFile' );
    this.label = new LabelTYPE();
    this.alt = '';
    this.ext = '';
    this.filename = '';
    this.description = '';
    this.tags = new TagsTYPE( $msgBlock );    
    this.file = null;    
    
    this.crops = new ImagesTYPE();
    
    this.$msgBlock = $msgBlock;
    
    this.jsID = 'file'+UID();
    
    this.uploadFormData = new FormData();
    
    var self = this;
    
    /**
     * parent extensions
     */
    this.save = function( callback, $el ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.save( self.toAJAX(), self.uploadFile,  callback, $el );    }
    this.get = function( callback, $el, async ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, $el, async ); 
     }    
    this.del = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.del(null, callback, $el, aysnc ); 
    }
    this.getID = function(){ return self.parent.getID(); }
    this.equals = function( file ){ return self.parent.equals( file ); }
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );
        self.label.fromAJAX( data );
        self.alt = data.alt;
        self.filename = data.filename;
        self.ext = self.getExtFromFilename( self.filename );
        self.description = data.description;
        if ( data.tags )
            self.fromAJAXtags( data.tags );
        if ( self.isImage() && data.crops )
            self.crops.fromAJAX( data.crops );
            
        return self;
    }
    
    this.fromAJAXtags = function( tags ){
        if (tags && tags.length){
            for(var i=0; i<tags.length; i++){
                var tag = tags[i];
                var oTag = new TagTYPE( self.$msgBlock );
                oTag.parent.id = tag.id;
                oTag.label.label = tag.label;
                self.tags.append( oTag );
            }
        }
    }

    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.alt = self.alt;
            if (self.ext)
                tmp.ext = self.ext;
            tmp.description = self.description;
            tmp.tags = self.toAJAXtabs();
        return tmp;
    }
    
    this.toAJAXtabs = function(){
        var tags = [];
        if ( self.tags && self.tags.tags() && self.tags.tags().length){
            for(var i=0; i<self.tags.tags().length; i++){
                var oTag = self.tags.tags()[i];
                var tag = {};
                tag.id = oTag.parent.id;
                tag.label = oTag.label.label;
                tags[ tags.length ] = tag;
            }
        }
        return tags;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function( params, tagParams ){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="file" data-id="'+self.parent.id+'" data-label="'+self.label.label+'" >';
        html+=      '<input type="hidden" class="fileID" value="'+self.parent.id+'" />';
        html+=      '<span class="type ext '+self.ext+' '+self.getType()+ '">';
        if ( self.isImage() )
            html+=      '<img src="'+UPLOADS_PATH+self.filename+'" />';
        else
            html+=      '&nbsp;'
        html+=      '</span>';
        html+=      '<span class="name">'+self.label.label+'</span>';
        html+=      '<span class="tags">'+self.mkTagPods( tagParams )+'</span>';
        html+=      '<span class="commands '+self.getType()+ '">';
        if ( params && params.editable ){
            if ( self.isImage() )
                html+=   '<button class="crop">crop</button>';
            html+=       '<button class="edit">edit</button>';
            html+=       '<button class="del">X</button>';
        }
        if ( params && params.selectable ){
            html+=       '<button class="selectMe">select</button>';                
        }
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    this.mkTagList = function( tags ){
        var html = '';
        if ( self.tags && self.tags.tags().length ){
            for ( var i = 0; i < self.tags.tags().length; i++){
                var tagID = self.tags.tags()[i].parent.id;
                var tag = tags.find( tagID );
                if (tag)
                    html += self.mkTag( tag, isImage );
            }
        }
        else
            html = '&nbsp;'
        return html;
    }
    
    this.mkTag = function( tag, isImage ){
        var html = '';
        html += '<div class="fileTag">'
        if ( self.isImage() && tag.style && tag.style.width && tag.style.height )
            html+= '<button class="cropWTag" data-id="'+tag.parent.id+'">'+tag.label.label+'</button>';
        else
            html+= tag.label.label;
        html += '</div>'
        return html;
    }
    
    this.mkTagPods = function(params){
        var html = '';
        if ( self.tags && self.tags.tags().length ){
            for ( var i = 0; i < self.tags.tags().length; i++){
                var tag = self.tags.tags()[i];
                html += self.mkTagPod( tag, params );
            }
        }
        else
            html = '&nbsp;'
        return html;
    }
    
    this.mkCropPods = function(){
        var html = '';
        if ( self.crops && self.crops.length ){
            for ( var i = 0; i < self.crops.length; i++){
                var crop = self.crops[i];
                html += self.mkCropPod( crop );
            }
        }
        else
            html = '&nbsp;'
        return html;
    }
    
    this.mkTagPod = function( tag, params ){
        var tagPod = new Pod();
        tagPod.id = tag.parent.id;
        tagPod.label = tag.label.label;
        return tagPod.mk( 'fileTag', params );
    }
    
    this.mkCropPod = function( crop ){
        var cropPod = new Pod();
        cropPod.id = crop.id;
        cropPod.label = crop.label;
        return cropPod.mk( 'fileCrop', 'fileCropEdit' );
    }
    
    this.readForm = function(){
        if ( $('#fileID').val() )
               self.parent.id = $('#fileID').val(); 
        self.label.label = $.trim($('#fileName').val());
        self.alt = $.trim($('#fileAlt').val());
        self.description = $.trim($('#fileDescription').val());
        self.readTags();
        return self;
    }
    
    this.readTags = function(){
        $('#fileTagArea .fileTag').each(function(){            var $this = $(this);
            var tag = new TagTYPE( self.$msgBlock );
            tag.parent.id = $this.find('.podID').val();
            tag.label.label = $this.find('span').html();
            self.tags.append(tag);
        })
    }
    
    this.uploadFile = function(){
        if ( self.file ){
            self.uploadFormData.append('file', self.file);
            self.uploadFormData.append('id', self.parent.id );
            self.uploadFormData.append('className', self.parent.childClass);
                        
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = self.completionHandler;
            xhr.open("POST", AJAX_UPLOAD_URL);
            xhr.send(self.uploadFormData);
            return true;
        }
        return false;
    }

    this.completionHandler = function(){
        if(this.readyState == 4 && this.status == 200){
            var data = $.parseJSON( this.responseText );
            if (  data.status)
                successMsg( self.$msgBlock, data.status );
            else 
                errorMsg( self.$msgBlock, data.status );
        }
        else
            errorMsg( self.$msgBlock, this.readyState );
    }
    
    this.fillForm = function(){
        if (self.parent.id)
            $('#fileID').val( self.parent.id );
        $('#fileName').val( self.label.label );    
        $('#fileDescription').val(self.description);
        $('#fileAlt').val(self.alt);
        if ( self.isImage() ){
            $('#fileAlt').parent('fieldset').show();
            $('#uploadPreview img').attr('src', UPLOADS_PATH+'/'+self.parent.id+'.'+self.ext+'?lastmod='+UID());
        }        
        $('#fileTagArea').html( self.mkTagPods() );
    }
    
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.parent.id) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    this.isImage = function(ext){  
        if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_IMAGES ) >= 0) 
    }
    this.isDoc = function(ext){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_DOCS ) >= 0) 
    }
    this.isVid = function(ext){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_VIDS ) >= 0) 
    }
    this.isAudio = function(ext){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_AUDIO ) >= 0) 
    }
    this.isValidType = function( ext ){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_FILES ) >= 0) 
    }
    this.isValidSize = function( size ){ return size <=  MAX_FILE_SIZE;}
    this.isValid = function( ext, size ){ return self.isValidType(ext) && self.isValidSize(size); }
    
    this.getType =function(ext){
        if (!ext)
            ext = self.ext;
        if (self.isImage(ext))
            return 'img';
        if (self.isDoc(ext))
            return 'doc';
        if (self.isVid(ext))
            return 'vid';
        if (self.isAudio(ext))
            return 'aud';
        return '';
    }
    
    this.getExtFromFilename = function (filename){
        ext = filename.split('.');
        return ext[ (ext.length -1) ].toLowerCase(); 
    }
    
    this.regex = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                suspect=new RegExp( suspect, 'i' );
                return suspect.test( self.label.label );
            default:
                return false;
                
        }
        return false;
    }
       
    $('body').on( 'click', '#'+self.jsID+' .edit', function(){
        self.fillForm();
    })
    
    $('body').on('click', '#'+self.jsID+' .del', function(){
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#fileList'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm', function(){
        self.del( null, self.$msgBlock, false );
    })
}
$.extend(FileTYPE, Node);
