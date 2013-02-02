function ImagesTYPE(){
    this.images = new ListTYPE();    
    var self = this;

    /**
    * methods
    */
    this.fromAJAX = function( response ){
        self.clear();
        if ( response && response.length ){
             for( var i=0; i < response.length; i++ ){
                 var data = response[i];
                 var image = new ImageTYPE();
                 image.fromAJAX( data );
                 self.append( image );
            }   
        }
    }
    
    this.findRatio = function( suspect ){
        for ( var i=0; i < self.count(); i++ ){
            var img = self.list()[i];
            if ( img.ratio.compare( suspect ) == 'equals' )
                return img;
        }        
        return null;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        return html;
    }
    /***/
    
    
    this.clear = function(){ self.images.clear(); }
    this.append = function( image ){ this.images.append( image ); }
    this.find = function( image ){  return self.images.find( image ); }
    this.list = function(){ return self.images.list(); }
    this.find = function( suspect ){ return self.images.find( suspect); }
    this.filter = function( suspect, by ){ return self.images.filter( suspect, by ); }
    this.remove = function( suspect ){ self.images.remove( suspect ); }    
    this.count = function(){ return self.images.count(); }
    this.isEmpty = function(){ return self.images.isEmpty(); }
    /***/

}