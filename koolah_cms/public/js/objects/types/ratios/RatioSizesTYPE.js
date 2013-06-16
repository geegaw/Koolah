/**
 * @fileOverview defines RatioSizesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatioSizesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\ratios
 * @class - handles data for a ratio sizes
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function RatioSizesTYPE($msgBlock){
    
    /**
     * sizes - array of sizes
     * @type array
     */
    this.sizes = [];
    
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
    this.get_class = function(){ return 'RatioSizesTYPE'; }

    /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.sizes = []; }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( size ){ self.sizes[ self.sizes.length ] = size; }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.sizes, suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new RatiosTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.sizes(), suspect, by ); 
        return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.sizes.length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( sizes ){
        self.clear();
        if ( sizes && sizes.length ){
             for( var i=0; i < sizes.length; i++ ){
                 var data = sizes[i];
                 var size = new RatioSizeTYPE(self.$msgBlock);
                 size.fromAJAX( data );
                 self.append( size );
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
        if ( self.sizes && self.sizes.length ){
             for( var i=0; i < self.sizes.length; i++ ){
                 var size = self.sizes[i];
                 tmp[tmp.length] = size.toAJAX();
            }   
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
        if ( self.sizes() ){
            for (var i=0; i < self.sizes().length; i++){
                var size = self.sizes()[i];
                if ( !onlyWStyle || !size.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = size.label.label;
                        dropDown.value  = size.parent.id;
                    }
                    else
                        dropDown = size.label.label;
                    list[ list.length ] = dropDown;
               }   
            }
        }
        return list;
    }
    
    /**
     * mkList
     * - make html list of ratio sizes
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        if ( self.sizes ){
            for (var i=0; i < self.sizes.length; i++){
                var size = self.sizes[i];
                html += size.mkList();   
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
        var pos = findPosInList(self.sizes, suspect);
        if (pos>=0)
            self.sizes.splice(pos, 1);
    }
}