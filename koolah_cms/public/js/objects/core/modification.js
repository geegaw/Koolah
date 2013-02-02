function Modification(){
	this.modifiedBy = '';
	this.modifiedOn = '';
	var self = this;

	this.toJSON = function(){
		var data = {};
			data.modifiedBy = self.modifiedBy;
			data.modifiedOn = self.modifiedOn;
		return data;
	}
	
	this.fromJSON = function( data ){
		self.modifiedBy = data.modifiedBy;
		self.modifiedOn = data.modifiedOn;
	}
	
}