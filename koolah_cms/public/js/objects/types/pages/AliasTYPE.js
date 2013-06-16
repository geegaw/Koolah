/**
 * @fileOverview defines AliasTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * AliasTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Node
 * @class - handles data related to an alias for a page
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function AliasTYPE($msgBlock){
   
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node('KoolahAlias');

    /**
     * alias - url alias
     * @type string
     * @default ''
     */
    this.alias = '';
    
    /**
     * $msgBlock - dom reference to where to display messages
     *@type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    /**
     * jsID - unique id for dom 
     *@type string
     */
    this.jsID = 'alias'+UID();
    
    /**
     * aliasDisplayParams - pod options 
     *@type obj
     */
    var aliasDisplayParams = { 'editable': true };
    
    var self = this;

    //*** parent extensions ***//
    /**
     * save
     * - calls ajax to save and displays the status
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.save = function(callback, $el) { self.parent.save(self.toAJAX(), null, callback, $el); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function(callback, $el, async) { self.parent.get(self.fromAJAX, callback, $el, async); }
    
    /**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function(callback, $el, async) { self.parent.del(null, callback, $el, async); }
    
    /**
     * getID:
     * - return id
     * @returns string id
     */
    this.getID = function() { return self.parent.getID(); }
    
    /**
     * equals
     * - compare two ids to determine
     * if object is same
     * @param string folder - suspect id
     * @returns bool
     */
    this.equals = function(alias) { return self.parent.equals(alias); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'AliasTYPE'; }
    //*** /parent extensions ***//

    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function(data){
        self.parent.id = data.id
        self.alias = data.alias;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {};
        tmp.alias = self.alias;
        tmp.id = self.parent.id;
        return tmp;
    }
    
    /**
     * mkPod
     * - make an alias pod
     * @returns string
     */
    this.mkPod = function(){
        var pod = new Pod( self.jsID );
        pod.id = self.parent.id;
        pod.label = self.alias;
        return pod.mk( 'alias', aliasDisplayParams );
    }
    
    /**
     * mkCapsule
     * - make an alias capsule
     * @returns string
     */
    this.mkCapsule = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="alias">';
        html+=    '<span class="aliasName">'+self.alias+'</span>';
        html+=    '<a href="#" class="del">X</a>'; 
        html+= '</li>';  
        return html;
    }

    /**
     * readForm
     * - read data from form and fill in data 
     */
    this.readForm = function(){
        var pod = new Pod( self.jsID );
        pod.read();
        self.alias = pod.label;
        self.parent.id = pod.id;
    }

    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function($alias){
        $alias.find('label').attr('for', self.jsID);
        $alias.find('input').attr('id', self.jsID).val( self.alias );
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
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    /**
     * isUnique
     * - check if alias is unique
     * - defaults to self if no alias is passed
     * @param AliasTYPE alias - optional
     * @returns bool
     */
    this.isUnique = function(alias){
        if (!alias)
            alias = self.alias;
        var q = ["alias="+alias];
        var tester = new AliasesTYPE( self.$msgBlock );
        tester.get(null, q, self.$msgBlock, false);
        return tester.isEmpty();    
    }
    
    /**
     * mkSafe
     * - replace spaces with -
     * - remove non numbers/letters, allow /-
     * - lowercase
     */
    this.mkSafe = function(){
        self.alias = self.alias.replace(/ /g, '-');
        self.alias = self.alias.replace(/[^0-9a-zA-Z-\/]/g, '');
        self.alis = self.alias.toLowerCase();
    }
}