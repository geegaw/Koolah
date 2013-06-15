/**
 * @fileOverview defines ModificationHistory
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * ModificationHistory
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\core
 * @class - keeps track of objects modifications
 * @constructor
 */
function ModificationHistory(){
	
	/**
     * history - array of elements
     *@type array
     */
    this.history = [];
	
	var self = this;
	
	/**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
		var data = {}
		    data.history = self.history;
	    return data;
	}
		
	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param json data
     */
    this.fromAJAX = function( data ){
		if ( data.history && data.history.length ){
			for ( var i=0; i<data.history.length; i++ ){
				var modification = new Modification();
				modification.fromAJAX( data.history[i] );
				self.append( modification );  
			}
		}
	}	
	
	/**
     * clear
     * - empties nodes
     */
    this.clear = function(){ this.history = []; }
	
	/**
     * append
     * - appends a node
     * @param mixed modification - node to append
     */
    this.append = function( modification ){ this.history[ this.history.length ] = modification; }
}