function ModificationHistory(){
	this.history = [];
	var self = this;
	
	this.toAJAX = function(){
		var data = {}
		    data.history = self.history;
	    return data;
	}
		
	this.fromAJAX = function( data ){
		if ( data.history && data.history.length ){
			for ( var i=0; i<data.history.length; i++ ){
				var modification = new Modification();
				modification.fromAJAX( data.history[i] );
				self.append( modification );  
			}
		}
	}	
	
	this.clear = function(){ this.history = []; }
	this.append = function( modification ){ this.history[ this.history.length ] = modification; }
}