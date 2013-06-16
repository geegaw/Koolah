/**
 * @fileOverview defines ImageTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ImageTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\uploads
 * @extends Node
 * @class - handles data for an image
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function ImageTYPE( $msgBlock ) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahImage' );
    
    /**
     * file - parent file
     * @type FileTYPE
     */
    this.file = new FileTYPE( $msgBlock );
    
    /**
     * ratio - ratio image has been cropped to
     * @type RatioTYPE
     */
    this.ratio = new RatioTYPE( $msgBlock );
    
    /**
     * crop - crop for image
     * @type CropTYPE
     */
    this.crop = new CropTYPE();
    
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
        self.parent.save( self.toAJAX(), null,  callback, $el );
    }
    
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
    this.get_class = function(){ return 'ImageTYPE'; }
    //*** /parent extensions ***//

    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );        
        self.file.parent.id = data.file;
        self.ratio.parent.id = data.ratio;
        self.crop.fromAJAX( data.crop )
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {};
        tmp.ratio = self.ratio.parent.id;
        tmp.file = self.file.parent.id;
        tmp.crop = self.crop.toAJAX();
        return tmp;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function(){
        self.crop.readForm(); 
        if ( $('#cropRatioID').val() ) 
            self.ratio.parent.id = $('#cropRatioID').val();
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        var coords = [ 0, 0, 150, 150 ];
        if ( self.crop && self.crop.coords )
            coords = self.crop.coordsToArray();
        
        $('#cropImg')
            .attr('src',  fileManager.formatUrl(self.file.parent.id) )
            .Jcrop({
                            bgColor:     '#000',
                            bgOpacity:   .25,
                            setSelect:  coords
                        },
                        function(){ 
                            self.crop.cropObj = this;
                            $('#cropImgHeight').css('height', $('#cropImg').height() );
                            $('#cropImgHeight span').html( $('#cropImg').height()+'px' )
                            $('#cropImgWidth').css('width', $('#cropImg').width() );;
                            $('#cropImgWidth span').html( $('#cropImg').width()+'px' )
                            koolahToolkit.center( $('#cropSection'), $(window), 'absolute' ); 
                        }
            );
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
     * regex
     * - compare two ratio sizes with regex
     * @param mixed suspect
     * @returns mixed|bool
     */
    this.regex = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                suspect=new RegExp( suspect );
                return suspect.test( self.label.label );
            default:
                return false;
                
        }
        return false;
    }
}