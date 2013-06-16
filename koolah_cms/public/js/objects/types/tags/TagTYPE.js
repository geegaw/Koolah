/**
 * @fileOverview defines TagTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TagTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\tags
 * @extends Node
 * @class - handles data for a tag
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function TagTYPE( $msgBlock ) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahTag' );
    
    /**
     * label - page label
     * @type LabelTYPE
     */
    this.label = new LabelTYPE();
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'tag'+UID();
    
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
    this.equals = function( tag ){ return self.parent.equals( tag ); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'TagTYPE'; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );
        self.label.fromAJAX( data );
    }

   /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
     this.toAJAX = function(){
        var tmp = self.label.toAJAX();
        return tmp;
    }
    
    /**
     * mkList
     * - make html list view of page
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="tag fullWidth">';
        html+=      '<span class="name tagName">'+self.label.label+'</span>';
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
        if ( $('#tagID').val() )
               self.parent.id = $('#tagID').val(); 
        self.label.label = $.trim($('#tagName').val());
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        if (self.parent.id)
            $('#tagID').val( self.parent.id );
        $('#tagName').val( self.label.label );    
    }
    
    /**
     * compare
     * - compare two tags
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
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#tagList'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm', function(){
        self.del( null, self.$msgBlock, false );
    })
}