function Nodes( className ){
	this.className = className;
	this.nodes = [];
	var self = this;

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
	
	this.clear = function(){ self.nodes = []; }
	this.append = function( node ){ self.nodes[ self.nodes.length ] = node;}
	
	this.find = function( node ){
		if ( self.nodes && self.nodes.length ){
			for ( var i=0; i < self.nodes.length; i++ ){
				if ( self.nodes[i].equals( node ) )
					return self.nodes[i];
			}
		}
		return null;
	}
}