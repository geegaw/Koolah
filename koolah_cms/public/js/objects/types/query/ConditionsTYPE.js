/**
 * @fileOverview defines ConditionsTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ConditionsTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\query
 * @class - multiple conditions for a query
 * @extends ListTYPE
 * @constructor
 */
function ConditionsTYPE(){
    
    /**
     * conditions - list of conditions
     *@type ListTYPE
     */
    this.conditions = new ListTYPE();    
    
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'ConditionsTYPE'; }
    
    /**
     * els
     * - easy call to get elements
     * @returns array
     */
    this.els = function(){ return self.conditions.elements; }
   
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
                 var condition = new ConditionTYPE();
                 condition.fromAJAX( data );
                 self.append( condition );
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
        if (self.conditions && !self.isEmpty() ){
            for( var i=0; i < self.count(); i++ )
                tmp[i] = self.conditions.elements[i].toAJAX();
        }    
        return tmp;    
    }
    
    /**
     * mkInput
     * - make html for menus 
     * @returns string
     */
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    /**
     * mkList
     * - make html list of conditions
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        return html;
    }
    
    
    //*** list extensions ***//
     /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.conditions.clear(); }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( condition ){ this.conditions.append( condition ); }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){  return self.conditions.find( suspect ); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ return self.conditions.filter( suspect, by ); }
    
    /**
     * list
     * - list elements
     * @returns array
     */
    this.list = function(){ return self.conditions.list(); }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){ self.conditions.remove( suspect ); }    
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.conditions.count(); }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return self.conditions.isEmpty(); }
}