/**
 * @fileOverview defines LabelTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * LabelTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\core
 * @class - defines an object to store both a display label, and a unique reference string
 * @TODO not fully integrated
 * @constructor
 */
function LabelTYPE(){
    
    /**
     * label - display label
     *@type string
     */
    this.label = '';
    
    /**
     * ref -unique reference
     *@type string
     */
    this.ref = '';
    
    var self = this;
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param json data
     */
    this.fromAJAX = function( data ){
        if (data){
            self.label = data.label;
            self.ref = data.ref;
        }        
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {}
            tmp.label = self.label;
            tmp.ref = self.ref;            
        return tmp;
    }
    
}
