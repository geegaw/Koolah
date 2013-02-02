function RatiosTYPE($msgBlock){
    this.parent = new Nodes( 'KoolahRatios' );
    
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
    this.append = function( Ratio ){ self.parent.append( Ratio ); }
    this.find = function( Ratio ){  return self.parent.find( Ratio ); }
    /***/
    /**
    * methods
    */
    this.ratios = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function( response ){
        self.clear();
        if ( response.nodes && response.nodes.length ){
             for( var i=0; i < response.nodes.length; i++ ){
                 var data = response.nodes[i];
                 var ratio = new RatioTYPE(self.$msgBlock);
                 ratio.fromAJAX( data );
                 self.append( ratio );
            }   
        }
    }
    
    this.getDropdownList = function( obj, onlyWStyle ){
        var list = [];
        if ( self.ratios() ){
            for (var i=0; i < self.ratios().length; i++){
                var ratio = self.ratios()[i];
                if ( !onlyWStyle || !ratio.style.isEmpty() ){
                    if ( obj ){
                        var dropDown = {}
                        dropDown.label = ratio.label.label;
                        dropDown.value  = ratio.parent.id;
                    }
                    else
                        dropDown = ratio.label.label;
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
        if ( self.ratios() ){
            for (var i=0; i < self.ratios().length; i++){
                var ratio = self.ratios()[i];
                html += ratio.mkList();   
            }
        }
        return html;
    }
    
    this.find = function( suspect ){ return findInList(self.ratios(), suspect); }
    this.filter = function( suspect, by ){ 
        var results = new RatiosTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.ratios(), suspect, by ); 
        return results;
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.ratios(), suspect);
        if (pos>=0)
            self.ratios().splice(pos, 1);
           
    }
    
    this.count = function(){ return self.ratios().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}