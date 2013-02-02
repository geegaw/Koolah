function RatioSizesTYPE($msgBlock){
    this.sizes = [];
    this.$msgBlock = $msgBlock;
    
    var self = this;

    this.clear = function(){ self.sizes = []; }
    this.append = function( size ){ self.sizes[ self.sizes.length ] = size; }
    this.find = function( size ){  return self.parent.find( size ); }
    /***/
    /**
    * methods
    */
    this.fromAJAX = function( sizes ){
        self.clear();
        if ( sizes && sizes.length ){
             for( var i=0; i < sizes.length; i++ ){
                 var data = sizes[i];
                 var size = new RatioSizeTYPE(self.$msgBlock);
                 size.fromAJAX( data );
                 self.append( size );
            }   
        }
    }
    
    this.toAJAX = function(){
        var tmp = [];
        if ( self.sizes && self.sizes.length ){
             for( var i=0; i < self.sizes.length; i++ ){
                 var size = self.sizes[i];
                 tmp[tmp.length] = size.toAJAX();
            }   
        }
        return tmp;
    }
    
    this.getDropdownList = function( obj, onlyWStyle ){
        var list = [];
        if ( self.sizes() ){
            for (var i=0; i < self.sizes().length; i++){
                var size = self.sizes()[i];
                if ( !onlyWStyle || !size.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = size.label.label;
                        dropDown.value  = size.parent.id;
                    }
                    else
                        dropDown = size.label.label;
                    list[ list.length ] = dropDown;
               }   
            }
        }
        return list;
    }
    
    this.readForm = function( $form){
        return self;
    }
    
    this.fillForm = function( $form){
        var html = '';
        return html;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        if ( self.sizes ){
            for (var i=0; i < self.sizes.length; i++){
                var size = self.sizes[i];
                html += size.mkList();   
            }
        }
        return html;
    }
    
    this.find = function( suspect ){ return findInList(self.sizes, suspect); }
    this.filter = function( suspect, by ){ 
        var results = new RatiosTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.sizes(), suspect, by ); 
        return results;
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.sizes, suspect);
        if (pos>=0)
            self.sizes.splice(pos, 1);
    }
    
    this.count = function(){ return self.sizes.length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}