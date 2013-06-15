/**
 * @fileOverview defines Node
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
function Node(childClass){
    
    /**
     * id - db id
     *@type string
     */
	this.id = '';
	
	/**
     * meta - meta data
     *@type MetaTYPE
     */
	this.meta = new MetaTYPE();
	
	/**
     * childClass - name of child class - should match php ajax object
     *@type string
     */
	this.childClass = childClass;
	
	var self = this;
    
    /**
     * save
     * - calls ajax to save and displays the status
     * @param mixed childData - objects data
     * @param string chlidCallback - function name
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
	this.save = function( childData, chlidCallback, callback, $el ){
		var json = '{"id": '+self.id+', "className": self.childClass, "data": childData }';
		$.ajax({
			url: AJAX_SAVE_URL, 
			type: 'POST',
			//contentType: 'application/json; charset=UTF-8',
			async: false,
			dataType: 'json',
			cache: false,
			data: {
					"id": self.id, 
					"className": self.childClass, 
					"data": JSON.stringify( childData )
				  }, 
			error: function(e){ errorMsg( $el, 'error' ); console.log(e)},
			success: function(response){
				if ( response.status ){
				    successMsg( $el );
				    if( !self.id && response.id)
				        self.id = response.id
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
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string chlidCallback - function name
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function( chlidCallback, callback, $el, async ){
	    if ( async !== false )
	       async = true;
        
        $.ajax({
            url: AJAX_GET_ONE_URL, 
            type: 'POST',
            dataType: 'json',
            async: async,
            cache: false,
            data: {
                'id': self.id,
                'className': self.childClass,
            },
            error: function(e){ errorMsg( $el, 'error' ); console.log(e)},
            success: function(response){
                if ( response.status && response.node){
                    //success
                    self.fromAJAX( response.node );
                    if (chlidCallback)
                        chlidCallback( response.node );
                    if (callback)
                        callback( response.node );
                }
                else
                    errorMsg( $el, response.msg );
            },
        })
    }
	
	/**
     * del
     * - deletes a node by id and classname stored internally
     * display status
     * @param string chlidCallback - function name
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function(chlidCallback, callback, $el, async){
	    async = (async)? true : false;
		$.ajax({
			url: AJAX_DEL_URL,
			type: 'POST',
			async: async,
			dataType: 'json',
			cache: false,
			data:{ 
					'id': self.id,
					'className': self.childClass, 
				 },
			error: function(e){ errorMsg( $el, 'error' ); console.log(e)},
			success: function(response){
					if ( response.status ){
						if (chlidCallback)
							chlidCallback( response );
						if ( callback )
							callback( response );
						successMsg( $el );
					}
					else
						errorMsg( $el, response.msg );
			},
		});
	}

	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param json data
     */
    this.fromAJAX = function( data ){
		self.id = self.getNodeID( data ); // get mongo id
		self.meta.fromAJAX( data.meta );
	}
	
	/**
     * getID:
     * - return id
     * @returns string id
     */
    this.getID = function(){ return self.id; }
	
	/**
     * equals
     * - compare two ids to determine
     * if object is same
     * @param string suspectID - suspect id
     * @returns bool
     */
    this.equals = function( suspectID ){ return ( suspectID == self.id ) }
    
    /**
     * getNodeID
     * - helper function
     * convert mongo object id into string
     * @param obj obj - mongo id object
     * @returns string|null
     */
    this.getNodeID = function( obj ){
        if ( obj._id && obj._id.$id )
            return obj._id.$id;
        return null;
    }
}