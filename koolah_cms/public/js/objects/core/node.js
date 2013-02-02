function Node(childClass){

	this.id = '';
	this.meta = new MetaTYPE();
	this.childClass = childClass;
	var self = this;

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

	this.get = function( chlidCallback, callback, $el, async ){
	    if ( async === false )
	       self.getSync( chlidCallback, callback, $el )
	    else
	       self.getAsync( chlidCallback, callback, $el )
	}
	
	this.getSync = function( chlidCallback, callback, $el ){    
        $.ajax({
            url: AJAX_GET_ONE_URL, 
            type: 'POST',
            dataType: 'json',
            async: false,
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
	
	this.getAsync = function( chlidCallback, callback, $el ){    
		$.ajax({
			url: AJAX_GET_ONE_URL, 
			type: 'POST',
			dataType: 'json',
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
	

	this.fromAJAX = function( data ){
		self.id = getNodeID( data ); // get mongo id
		self.meta.fromAJAX( data.meta ); 
	}
	
	this.getID = function(){ return self.id; }
	this.equals = function( id ){ return ( id == self.id ) }
}

function getNodeID( obj ){
	if ( obj._id && obj._id.$id )
		return obj._id.$id;
	return null;
}
