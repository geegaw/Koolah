/**
 * @fileOverview defines TagsTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TagsTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\tags
 * @extends Nodes
 * @class - works with multiple pages
 * @constructor
 * @param jQuery dom object $el
 */
function TagsTYPE($msgBlock){
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahTags' );
    
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
    this.get_class = function(){ return 'TagsTYPE'; }
    
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
    this.append = function( Tag ){ self.parent.append( Tag ); }
    
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
    this.find = function( suspect ){ return findInList(self.tags(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new TagsTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.tags(), suspect, by ); 
        return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.tags().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * tags
     * - easy call to get nodes
     * @returns array
     */
    this.tags = function(){ return self.parent.nodes; }
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
                 var tag = new TagTYPE(self.$msgBlock);
                 tag.fromAJAX( data );
                 self.append( tag );
            }   
        }
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
        if ( self.tags() ){
            for (var i=0; i < self.tags().length; i++){
                var tag = self.tags()[i];
                if ( !onlyWStyle || !tag.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = tag.label.label;
                        dropDown.value  = tag.parent.id;
                    }
                    else
                        dropDown = tag.label.label;
                    list[ list.length ] = dropDown;
               }   
            }
        }
        return list;
    }
    
    /**
     * mkList
     * - make html list of tags
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        if ( self.tags() ){
            for (var i=0; i < self.tags().length; i++){
                var tag = self.tags()[i];
                html += tag.mkList();   
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
        var pos = findPosInList(self.tags(), suspect);
        if (pos>=0)
            self.tags().splice(pos, 1);
    }
}