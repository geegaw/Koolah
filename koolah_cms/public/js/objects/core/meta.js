/**
 * @fileOverview defines MetaTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MetaTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\core
 * @class - defines oject meta data information class
 * @constructor
 */
function MetaTYPE(){
	
	/**
     * createdBy - id of user
     *@type string
     */
    this.createdBy = '';
	
	/**
     * createdAt - date of creation
     *@type string (date)
     */
    this.createdAt = '';
	
	/**
     * modificationHistory - further modification history
     *@type ModificationHistory
     */
    this.modificationHistory = new ModificationHistory();
	
	var self = this;
	
	/**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
		var data = {}
		    data.createdBy = self.createdBy;
			data.createdAt = self.createdAt;
			data.modificationHistory = self.modificationHistory.toAJAX();
	    return data;
	}
		
	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param json data
     */
    this.fromAJAX = function( data ){
        if (data){
    		self.createdBy = data.creationData.created_by;
    		self.createdAt = data.creationData.created_at;    
    		self.modificationHistory.fromAJAX( data.modificationHistory );
        }
	}
	
}