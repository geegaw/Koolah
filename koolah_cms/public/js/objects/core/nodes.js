/**
 * @fileOverview defines Nodes
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Node
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\core
 * @class crux of Koolah
 * @constructor
 * @param string childClass - name of child class
 */
function Nodes( className ){
	
	/**
     * childClass - name of child class
     *@type string
     */
    this.className = className;
	
	/**
     * nodes - array of elements
     *@type array
     */
    this.nodes = [];
    
	var self = this;

	/**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string chlidCallback - function name
     * @param string callback - function name
     * @param mixed args - query arguments
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function( chlidCallback, callback, args, $el, async ){
		$.ajax({
		    url: AJAX_GET_URL,
			type: 'POST',
			async: async,
			dataType: 'json',
			cache: false,
			data: {
				'className': self.className,
				'args': args
			},
			error: function(e){ errorMsg( $el, 'error' ); console.log(e)},
			success: function(response){
				if ( response.status ){
					//success
					self.nodes = response.nodes;
					if (chlidCallback)
						chlidCallback( response );
					if (callback)
						callback( response );
				}
				else
					errorMsg( $el, response.msg );
				
			},
		})
	}
	
	/**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.nodes = []; }
	
	/**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( node ){ self.nodes[ self.nodes.length ] = node; }
	
	/**
     * find
     * - look for suspect and return the node if found
     * @param string suspectID - suspect id
     * @returns Node|null
     */
    this.find = function( suspectID ){
		if ( self.nodes && self.nodes.length ){
			for ( var i=0; i < self.nodes.length; i++ ){
				if ( self.nodes[i].equals( suspectID ) )
					return self.nodes[i];
			}
		}
		return null;
	}
}