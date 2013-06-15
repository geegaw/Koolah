/**
 * @fileOverview defines Modification
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Modification
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\core
 * @class - modification object to store objects modification information
 * @constructor
 */
function Modification(){
	
	/**
     * modifiedBy - id of user
     *@type string
     */
    this.modifiedBy = '';
	
	/**
     * modifiedOn - date of modification
     *@type string (date)
     */
    this.modifiedOn = '';
	
	var self = this;

	/**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
		var data = {};
			data.modifiedBy = self.modifiedBy;
			data.modifiedOn = self.modifiedOn;
		return data;
	}
	
	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param json data
     */
    this.fromAJAX = function( data ){
		self.modifiedBy = data.modifiedBy;
		self.modifiedOn = data.modifiedOn;
	}
	
}