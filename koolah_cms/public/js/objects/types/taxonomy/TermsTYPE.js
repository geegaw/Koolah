/**
 * @fileOverview defines TermsTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TermsTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\terms
 * @extends Nodes
 * @class - works with multiple terms
 * @constructor
 * @param jQuery dom object $el
 */
function TermsTYPE($msgBlock){
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahTerms' );
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'TermsTYPE'; }
    
    //*** parent extensions ***//
    /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.parent.clear(); }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( Term ){ self.parent.append( Term ); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool aync
     */
    this.get = function( callback, args, $el, aysnc ){
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, args, $el, aysnc ); 
    }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.terms(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new TermsTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.terms(), suspect, by ); 
        return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.terms().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * terms
     * - easy call to get nodes
     * @returns array
     */
    this.terms = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( response ){
        self.clear();
        if ( response.nodes && response.nodes.length ){
             for( var i=0; i < response.nodes.length; i++ ){
                 var data = response.nodes[i];
                 var term = new TermTYPE(self.$msgBlock);
                 term.fromAJAX( data );
                 self.append( term );
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
        var tmp = [];
        for( var i=0; i < self.terms().length; i++ ){
             var term = self.terms()[i];
             tmp.push( term.toAJAX() );
        }
        return tmp;
    }
    
    /**
     * getDropdownList
     * - get drop down list for jQuery autocomplete 
     * @param obj obj
     * @param bool onlyWStyle - just show label
     * @returns array
     */
    this.getDropdownList = function( obj, onlyWStyle ){
        var list = [];
        if ( self.terms() ){
            for (var i=0; i < self.terms().length; i++){
                var term = self.terms()[i];
                if ( !onlyWStyle || !term.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = term.label.label;
                        dropDown.value  = term.parent.id;
                    }
                    else
                        dropDown = term.label.label;
                    list[ list.length ] = dropDown;
               }   
            }
        }
        return list;
    }
    
    /**
     * mkList
     * - make html list of terms
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        if ( self.terms() ){
            for (var i=0; i < self.terms().length; i++){
                var term = self.terms()[i];
                html += term.mkList(term);   
            }
        }
        return html;
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.terms(), suspect);
        if (pos>=0)
            self.terms().splice(pos, 1);
    }
}