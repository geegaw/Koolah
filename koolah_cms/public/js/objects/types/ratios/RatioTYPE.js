/**
 * @fileOverview defines RatioTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatioTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Node
 * @class - handles data for a photo ratio
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function RatioTYPE( $msgBlock ) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahRatio' );
    
    /**
     * label - page label
     * @type LabelTYPE
     */
    this.label = new LabelTYPE();
    
    /**
     * w - width
     * @type int
     * @default 0
     */
    this.w = 0;    
    
    /**
     * h - height
     * @type int
     * @default 0
     */
    this.h = 0;
    
    /**
     * sizes - different ratio sizes
     * @type RatioSizesTYPE
     */
    this.sizes = new RatioSizesTYPE( $msgBlock );
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'ratio'+UID();
    
    var self = this;
    
    //*** parent extensions ***//
    /**
     * save
     * - calls ajax to save and displays the status
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.save = function( callback, $el ){ 
        console.log( self.toAJAX() )
        
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
    this.get = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, $el, aysnc ); 
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
    this.equals = function( ratio ){ return self.parent.equals( ratio ); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'RatioTYPE'; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );
        self.label.fromAJAX( data );
        if ( data.w )
            self.w = data.w;
        if ( data.h )
            self.h = data.h;
        if (data.sizes)
            self.sizes.fromAJAX( data.sizes );
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.w = self.w;
            tmp.h = self.h;
            tmp.sizes = self.sizes.toAJAX();
        return tmp;
    }
    
    /**
     * mkList
     * - make html list view of page
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="ratio" data-id="'+self.parent.id+'">';
        html+=      '<span class="name ratioName">'+self.label.label+'</span>';
        html+=      '<span class="commands">';
        html+=          '<button class="edit">edit</button>';
        html+=          '<button class="del">X</button>';
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form){
        if ($.trim($('#ratioID').val()).length )
            self.parent.id = $.trim( $('#ratioID').val() );
        self.label.label = $.trim($('#ratioName').val());
        self.w = $.trim($('#ratioWidth').val());
        self.h = $.trim($('#ratioHeight').val());
        //self.sizes.readForm();
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        if (self.parent.id)
            $('#ratioID').val( self.parent.id );
        $('#ratioName').val( self.label.label );   
        $('#ratioWidth').val( self.w ) 
        $('#ratioHeight').val( self.h ) 
        $('#ratioSizesList ul').html( self.sizes.mkList() );
    }
    
    /**
     * compare
     * - compare two folders
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
    
    /** Dom actions **/   
    $('body').on( 'click', '#'+self.jsID+' .edit', function(){
        self.fillForm();
    })
    
    $('body').on('click', '#'+self.jsID+' .del', function(){
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#ratios'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm', function(){
        self.del( null, self.$msgBlock, false );
    })
    
}