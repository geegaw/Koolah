function TagsTYPE($msgBlock){
    this.parent = new Nodes( 'KoolahTags' );
    
    this.$msgBlock = $msgBlock;
    
    var self = this;

    /**
     * parent extensions
     */
    this.get = function( callback, args, $el, aysnc ){
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, args, $el, aysnc ); 
    }
    this.clear = function(){ self.parent.clear(); }
    this.append = function( Tag ){ self.parent.append( Tag ); }
    this.find = function( Tag ){  return self.parent.find( Tag ); }
    /***/
    /**
    * methods
    */
    this.tags = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function( response ){
        self.clear();
        if ( response.nodes && response.nodes.length ){
             for( var i=0; i < response.nodes.length; i++ ){
                 var data = response.nodes[i];
                 var tag = new TagTYPE(self.$msgBlock);
                 tag.fromAJAX( data );
                 self.append( tag );
            }   
        }
    }
    
    this.getDropdownList = function( obj, onlyWStyle ){
        var list = [];
        if ( self.tags() ){
            for (var i=0; i < self.tags().length; i++){
                var tag = self.tags()[i];
                if ( !onlyWStyle || !tag.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = tag.label.label;
                        dropDown.value  = tag.parent.id;
                    }
                    else
                        dropDown = tag.label.label;
                    list[ list.length ] = dropDown;
               }   
            }
        }
        return list;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        if ( self.tags() ){
            for (var i=0; i < self.tags().length; i++){
                var tag = self.tags()[i];
                html += tag.mkList();   
            }
        }
        return html;
    }
    
    this.find = function( suspect ){ return findInList(self.tags(), suspect); }
    this.filter = function( suspect, by ){ 
        var results = new TagsTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.tags(), suspect, by ); 
        return results;
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.tags(), suspect);
        if (pos>=0)
            self.tags().splice(pos, 1);
           
    }
    
    this.count = function(){ return self.tags().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}