/**
 * @fileOverview defines ImagesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ImagesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\uploads
 * @class - handles multiple images
 * @extends ListTYPE
 * @constructor
 */
function ImagesTYPE(){
    
    /**
     * images - list of images
     *@type ListTYPE
     */
    this.images = new ListTYPE();    
    
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'ImagesTYPE'; }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( response ){
        self.clear();
        if ( response && response.length ){
             for( var i=0; i < response.length; i++ ){
                 var data = response[i];
                 var image = new ImageTYPE();
                 image.fromAJAX( data );
                 self.append( image );
            }   
        }
    }
    
    /**
     * findRatio
     * - finds by ratio
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.findRatio = function( suspect ){
        for ( var i=0; i < self.count(); i++ ){
            var img = self.list()[i];
            if ( img.ratio.compare( suspect ) == 'equals' )
                return img;
        }        
        return null;
    }
    
    //*** list extensions ***//
     /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.images.clear(); }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( image ){ this.images.append( image ); }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return self.images.find( suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ return self.images.filter( suspect, by ); }
    
    /**
     * list
     * - list elements
     * @returns array
     */
    this.list = function(){ return self.images.list(); }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){ self.images.remove( suspect ); }    
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.images.count(); }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return self.images.isEmpty(); }
}