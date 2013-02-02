function MetaTYPE(){
	this.createdBy = '';
	this.createdAt = '';
	this.modificationHistory = new ModificationHistory();
	var self = this;
	
	this.toAJAX = function(){
		var data = {}
		    data.createdBy = self.createdBy;
			data.createdAt = self.createdAt;
			data.modificationHistory = self.modificationHistory.toAJAX();
	    return data;
	}
		
	this.fromAJAX = function( data ){
        if (data){
    		self.createdBy = data.creationData.created_by;
    		self.createdAt = data.creationData.created_at;    
    		self.modificationHistory.fromAJAX( data.modificationHistory );
        }
	}
	
}