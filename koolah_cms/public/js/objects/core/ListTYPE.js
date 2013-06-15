/**
 * @fileOverview defines ListTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ListTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\core
 * @class - beginnings of a custom array class
 * @TODO not fully integrated
 * @constructor
 */
function ListTYPE(){
    
    /**
     * elements - array elements
     *@type array
     */
    this.elements = [];
    
    var self = this;
    
    /**
     * clear
     * - empties elements
     */
    this.clear = function(){ 
        self.elements = []; 
    }
    
    /**
     * append
     * - appends a node
     * @param mixed modification - node to append
     */
    this.append = function( node ){ 
        self.elements[ self.count() ] = node; 
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ 
        return self.elements.length; 
    }
    
    /**
     * list
     * - returns the elements
     * @returns array
     */
    this.list = function(){ 
        return self.elements; 
    }
     
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){
        var results = new ListTYPE();
        
        for ( var i=0; i< self.count(); i++ ){
            var node = self.elements[i];
            if ( typeof node == 'object' ){
                if ( by == 'regex' && node.regex != undefined){
                    if ( node.regex( suspect ) ){
                         results.append( node );
                    }                         
                }
                else if( by == 'exact' && node.compare != undefined){
                    if ( node.compare( suspect ) == 'equals' )
                         results.append( node ); 
                }    
            }
            else{
                if ( by == 'regex'){
                    suspect=new RegExp( suspect );
                    if ( suspect.test( node ) )   
                        results.append( node );
                }
                else if( by == 'exact'){
                    if ( node == suspect )
                         results.append( node ); 
                }
            }
        }
        
        return results;
    }
    
    /**
     * findPos
     * - finds the position of suspect in the list
     * \n- returns -1 if not found
     * @param mixed suspect - suspect to look for
     * @returns int
     */
    this.findPos = function( suspect ){
        var node = self.elements[i];
        for ( var i=0; i<list.length; i++ ){
            if ( typeof node == 'object' && node.compare != 'undefined'){
                if ( node.compare( suspect ) == 'equals' )
                    return i; 
            }
            else{
                if ( node == suspect )
                    return i;
            }
        }
        
        return -1;
    }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){        
        pos = self.findPos( suspect );
        if ( pos >= 0 )
            return self.elements[pos];
       return null;
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = self.findPos( suspect);
        if (pos>=0)
            self.elements.splice(pos, 1);           
    }
    
    /**
     * has
     * - tells you if suspect is in the list
     * @param mixed suspect - suspect to look for
     * @returns bool
     */
    this.has = function( suspect ){ 
        return Boolean( self.findInList( suspect ) ); 
    }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ 
        return !Boolean(self.count() ); 
    }
}
