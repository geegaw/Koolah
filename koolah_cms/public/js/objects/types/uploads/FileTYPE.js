/**
 * @fileOverview defines FileTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FileTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\uploads
 * @extends Node
 * @class - handles data for a file
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function FileTYPE( $msgBlock ) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahFile' );
    
    /**
     * label - page label
     * @type LabelTYPE
     */
    this.label = new LabelTYPE();
    
    /**
     * alt - alt text for an image 
     * @type string
     * @default ''
     */
    this.alt = '';
    
    /**
     * ext - file extension 
     * @type string
     * @default ''
     */
    this.ext = '';
    
    /**
     * filename - file name 
     * @type string
     * @default ''
     */
    this.filename = '';
    
    /**
     * description - file description 
     * @type string
     * @default ''
     */
    this.description = '';
    
    /**
     * tags - tags about file 
     * @type TagsTYPE
     */
    this.tags = new TagsTYPE( $msgBlock );    
    
    /**
     * file - file 
     * @type string
     * @default null
     */
    this.file = null;    
    
    /**
     * crops - list of images that have been
     * cropped from this file 
     * @type ImagesTYPE
     */
    this.crops = new ImagesTYPE();
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'file'+UID();
    
    /**
     * uploadFormData - html5 file upload
     *  @type FormData
     */
    this.uploadFormData = new FormData();
    
    var self = this;
    
    //*** parent extensions ***//
    /**
     * save
     * - calls ajax to save and displays the status
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.save = function( callback, $el ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.save( self.toAJAX(), self.uploadFile,  callback, $el );    }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function( callback, $el, async ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, $el, async ); 
     }    
    
    /**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.del(null, callback, $el, aysnc ); 
    }
    
    /**
     * getID:
     * - return id
     * @returns string id
     */
    this.getID = function(){ return self.parent.getID(); }
    
    /**
     * equals
     * - compare two ids to determine
     * if object is same
     * @param string folder - suspect id
     * @returns bool
     */
    this.equals = function( file ){ return self.parent.equals( file ); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'FileTYPE'; }
    //*** /parent extensions ***//

    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
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
    
    /**
     * fromAJAXtags
     * - read in tags from data
     * - NOTE: we dont store all of a tags data here
     * @param array data
     */
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

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.alt = self.alt;
            if (self.ext)
                tmp.ext = self.ext;
            tmp.description = self.description;
            tmp.tags = self.toAJAXtabs();
        return tmp;
    }
    
    /**
     * toAJAXtabs
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * - NOTE: we dont store all of a tags data here
     * @returns object
     */
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
    
    /**
     * mkList
     * - make html list view of files
     * @param object params - pod options
     * @param object tagParams - pod options for tags
     * @returns string
     */
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
    
    /**
     * mkTagList
     * - make html list view of the file's tags
     * @param array tags - array of tags
     * @returns string
     */
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
    
    /**
     * mkTag
     * - make html list view of a file's tag
     * @param array tags - array of tags
     * @param book isImage
     * @returns string
     */
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
    
    /**
     * mkTagPods
     * - make html pods of tag
     * @param object params - pod options
     * @returns string
     */
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
    
    /**
     * mkCropPods
     * - make html pods of crop
     * @param object params - pod options
     * @returns string
     */
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
    
    /**
     * mkTagPod
     * - make html pod of tag
     * @param TagTYPE tag
     * @param object params - pod options
     * @returns string
     */
    this.mkTagPod = function( tag, params ){
        var tagPod = new Pod();
        tagPod.id = tag.parent.id;
        tagPod.label = tag.label.label;
        return tagPod.mk( 'fileTag', params );
    }
    
    /**
     * mkCropPod
     * - make html pod of tag
     * @param ImageTYPE crop
     * @param object params - pod options
     * @returns string
     */
    this.mkCropPod = function( crop ){
        var cropPod = new Pod();
        cropPod.id = crop.id;
        cropPod.label = crop.label;
        return cropPod.mk( 'fileCrop', 'fileCropEdit' );
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * return FileTYPE
     */
    this.readForm = function(){
        if ( $('#fileID').val() )
               self.parent.id = $('#fileID').val(); 
        self.label.label = $.trim($('#fileName').val());
        self.alt = $.trim($('#fileAlt').val());
        self.description = $.trim($('#fileDescription').val());
        self.readTags();
        return self;
    }
    
    /**
     * readTags
     * - read file tags data
     */
    this.readTags = function(){
        $('#fileTagArea .fileTag').each(function(){            var $this = $(this);
            var tag = new TagTYPE( self.$msgBlock );
            tag.parent.id = $this.find('.podID').val();
            tag.label.label = $this.find('span').html();
            self.tags.append(tag);
        })
    }
    
    /**
     * uploadFile
     * - handle upload of a file and return
     * if succesful
     * @returns bool
     */
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

    /**
     * completionHandler
     * - display message upon completion of a file upload
     * if succesful
     */
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
    
    /**
     * fillForm
     * - fill in a form 
     */
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
    
    /**
     * compare
     * - compare two pages
     * - can expand this function to accept more
     * types, and/or return more then equals 
     * @param mixed suspect
     * @returns mixed|bool
     */
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.parent.id) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    /**
     * isImage
     * - if extension passed or self is an image
     * @params string ext - optional - extension
     * @returns bool
     */
    this.isImage = function(ext){  
        if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_IMAGES ) >= 0) 
    }
    
    /**
     * isImage
     * - if extension passed or self is an image
     * @params string ext - optional - extension
     * @returns bool
     */
    this.isDoc = function(ext){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_DOCS ) >= 0) 
    }
    
    /**
     * isVid
     * - if extension passed or self is a video
     * @params string ext - optional - extension
     * @returns bool
     */
    this.isVid = function(ext){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_VIDS ) >= 0) 
    }
    
    /**
     * isAudio
     * - if extension passed or self is an audio file
     * @params string ext - optional - extension
     * @returns bool
     */
    this.isAudio = function(ext){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_AUDIO ) >= 0) 
    }
    
    /**
     * isValidType
     * - if extension passed or self is a valid file type
     * @params string ext - optional - extension
     * @returns bool
     */
    this.isValidType = function( ext ){  
         if (!ext)
            ext = self.ext;
        return ($.inArray( ext, VALID_FILES ) >= 0) 
    }
    
    /**
     * isValidSize
     * - if size is inside of acceptable boundaries
     * @params int size - file size
     * @returns bool
     */
    this.isValidSize = function( size ){ return size <=  MAX_FILE_SIZE;}
    
    /**
     * isValid
     * - if extension is valid and size is valid
     * @params string ext extension
     * @params int size - file size
     * @returns bool
     */
    this.isValid = function( ext, size ){ return self.isValidType(ext) && self.isValidSize(size); }
    
    /**
     * getType
     * - tell the type of an extension passed or self
     * @params string ext - optional - extension
     * @returns string
     */
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
    
    /**
     * getExtFromFilename
     * - get the extension off of a filename
     * @params string filename
     * @returns string
     */
    this.getExtFromFilename = function (filename){
        ext = filename.split('.');
        return ext[ (ext.length -1) ].toLowerCase(); 
    }
    
    /**
     * regex
     * - compare two ratio sizes with regex
     * @param mixed suspect
     * @returns mixed|bool
     */
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
       
    /** Dom actions **/   
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