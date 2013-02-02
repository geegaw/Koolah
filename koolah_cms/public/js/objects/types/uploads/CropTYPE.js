function CropTYPE(){
    this.w = 0;
    this.h = 0;    
    this.coords = null;
    
    this.cropObj = null;
    
    var self = this;
    
    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.w = data.w;
        self.h = data.h;
        self.coords = data.coords;
    }

    this.toAJAX = function(){
        var tmp = {};
            tmp.w = self.w;
            tmp.h = self.h;
            tmp.coords = self.coords;
        return tmp;
    }
    
    this.readForm = function( $form){
        self.coords = self.cropObj.tellSelect();
        
        self.w = parseInt( $('#width').val() );
        self.h = parseInt( $('#heigth').val() );
        
        if ( !self.w || !self.h){
            self.w = self.coords.w;
            self.h = self.coords.h;
        }
        
        return self;
    }
    
    this.fillForm = function(){
        $('#width').val( self.w );
        $('#height').val( self.h );
    }
    
    this.isEmpty = function(){
        return ( !self.w || !self.h );
    }   
    
    this.coordsToArray = function(){
        arr = [];
        if ( self.coords )
            arr = [ self.coords.x, self.coords.y, self.coords.x2, self.coords.y2 ]; 
        return arr;
    }
    
}