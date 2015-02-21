alert('Node')
var Node = Backbone.Model.extend({
    defaults: {
        id: '',
		meta: new MetaTYPE(),
		childClass: ''
    },
    
    save: function( childData, chlidCallback, callback, $el ){
		var json = '{"id": '+self.id+', "className": self.childClass, "data": childData }';
		$.ajax({
			url: AJAX_SAVE_URL, 
			type: 'POST',
			async: false,
			dataType: 'json',
			cache: false,
			data: {
					"id": self.id, 
					"className": self.childClass, 
					"data": JSON.stringify( childData )
				  }, 
			error: function(e){ errorMsg( $el, 'error' ); console.log(e); },
			success: function(response){
				if ( response.status ){
				    successMsg( $el );
				    if( !self.id && response.id)
				        self.id = response.id;
					if (callback)
						callback( response );	
					if (chlidCallback)
						chlidCallback( response );
				}
				else
					errorMsg( $el, response.msg );
			},
		})
	},
	
	get: function( chlidCallback, callback, $el, async ){
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
            error: function(e){ errorMsg( $el, 'error' ); console.log(e); },
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
   },
   
   del: function(chlidCallback, callback, $el, async){
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
			error: function(e){ errorMsg( $el, 'error' ); console.log(e); },
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
	},
	fromAJAX: function( data ){
		self.id = self.getNodeID( data ); // get mongo id
		self.meta.fromAJAX( data.meta );
	},
	getID: function(){ return self.id; },
	equals: function( suspectID ){ return ( suspectID == self.id ); },
	getNodeID: function( obj ){
        if ( obj._id && obj._id.$id )
            return obj._id.$id;
        return null;
    }
});