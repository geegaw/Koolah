function LabelTYPE(){
    this.label = '';
    this.ref = '';
    
    var self = this;
    
    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.label = data.label;
        self.ref = data.ref;        
    }

    this.toAJAX = function(){
        var tmp = {}
            tmp.label = self.label;
            tmp.ref = self.ref;            
        return tmp;
    }
    
}
