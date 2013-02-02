function FilesTYPE($msgBlock){
    this.parent = new Nodes( 'KoolahFiles' );
    
    this.$msgBlock = $msgBlock;
    
    var self = this;

    /**
     * parent extensions
     */
    this.get = function( callback, args, $el, async ){
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, args, $el, async ); 
    }
    this.clear = function(){ self.parent.clear(); }
    this.append = function( file ){ self.parent.append( file ); }
    this.find = function( file ){  return self.parent.find( file ); }
    /***/
    /**
    * methods
    */
    this.files = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function( response ){
        self.clear();
        if ( response.nodes && response.nodes.length ){
             for( var i=0; i < response.nodes.length; i++ ){
                 var data = response.nodes[i];
                 var file = new FileTYPE(self.$msgBlock);
                 file.fromAJAX( data );
                 self.append( file );
            }   
        }
    }
    
    this.getDropdownList = function( obj ){
        var list = [];
        if ( self.files() ){
            for (var i=0; i < self.files().length; i++){
                var file = self.files()[i];
                if ( obj ){
                    var dropDown = {}
                    dropDown.label = file.label.label;
                    dropDown.value  = file.parent.id;
                }
                else
                    dropDown = file.label.label;
                list[ list.length ] = dropDown;   
            }
        }
        return list;
    }
    
    this.getExtDropdownList = function( obj ){
        var list = [];
        if ( self.files() ){
            for (var i=0; i < self.files().length; i++){
                var file = self.files()[i];
                if ( obj ){
                    var dropDown = {}
                    dropDown.label = file.ext;
                    dropDown.value  = file.parent.id;
                }
                else
                    dropDown = file.ext;
                list[ list.length ] = dropDown;   
            }
        }
        return list;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function( params, tagParams ){
        var html = '';
        if ( self.files() ){
            for (var i=0; i < self.files().length; i++){
                var file = self.files()[i];
                html += file.mkList( params, tagParams );   
            }
        }
        return html;
    }
    
    this.find = function( suspect ){ return findInList(self.files(), suspect); }
    this.filter = function( suspect, by ){ 
        var results = new FilesTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.files(), suspect, by ); 
        return results;
    }
    
    this.filterByType = function( suspect ){ 
        var results = new FilesTYPE( self.$msgBlock );
        var comparison = 'is'+suspect;
        
        for ( var i = 0; i < self.files().length; i++ ){
            var file = self.files()[i];
            if ( eval( 'file.'+comparison+'()' ) )
                results.append( file );    
        }
         
        return results;
    }
    
    this.filterByExt = function( suspect ){ 
        suspect = suspect.toLowerCase();
        
        var results = new FilesTYPE( self.$msgBlock );
        
        for ( var i = 0; i < self.files().length; i++ ){
            var file = self.files()[i];
            if ( file.ext == suspect )
                results.append( file );    
        }         
        return results;
    }
    
    this.filterByTag = function( suspect ){ 
        var results = new FilesTYPE( self.$msgBlock );
        
        for ( var i = 0; i < self.files().length; i++ ){
            var file = self.files()[i];
            if ( file.tags.tags().length ){
                var hasTag = false;
                for ( var j=0; j < file.tags.tags().length && !hasTag; j++){
                    var tag = file.tags.tags()[j];
                    if ( tag.regex( suspect ) ){
                        hasTag = true;
                        results.append( file );           
                    }
                }
            }       
        }         
        return results;
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.files(), suspect);
        if (pos>=0)
            self.files().splice(pos, 1);
           
    }
    
    this.count = function(){ return self.files().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}