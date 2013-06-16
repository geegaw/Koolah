/**
 * @fileOverview defines RatiosTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatiosTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\ratios
 * @extends Nodes
 * @class - works with multiple ratios
 * @constructor
 * @param jQuery dom object $el
 */
function RatiosTYPE($msgBlock){
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahRatios' );
    
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
    this.get_class = function(){ return 'RatiosTYPE'; }
    
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
    this.append = function( Ratio ){ self.parent.append( Ratio ); }
    
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
    this.find = function( suspect ){ return findInList(self.ratios(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new RatiosTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.ratios(), suspect, by ); 
        return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.ratios().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * ratios
     * - easy call to get nodes
     * @returns array
     */
    this.ratios = function(){ return self.parent.nodes; }
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
                 var ratio = new RatioTYPE(self.$msgBlock);
                 ratio.fromAJAX( data );
                 self.append( ratio );
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
        if ( self.ratios() ){
            for (var i=0; i < self.ratios().length; i++){
                var ratio = self.ratios()[i];
                if ( !onlyWStyle || !ratio.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = ratio.label.label;
                        dropDown.value  = ratio.parent.id;
                    }
                    else
                        dropDown = ratio.label.label;
                    list[ list.length ] = dropDown;
               }   
            }
        }
        return list;
    }
    
    /**
     * mkList
     * - make html list of ratios
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        if ( self.ratios() ){
            for (var i=0; i < self.ratios().length; i++){
                var ratio = self.ratios()[i];
                html += ratio.mkList();   
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
        var pos = findPosInList(self.ratios(), suspect);
        if (pos>=0)
            self.ratios().splice(pos, 1);
    }
}