function MenusTYPE($msgBlock){
    this.parent = new Nodes( 'KoolahMenus' );
    this.$msgBlock = $msgBlock;
    var self = this;

    /**
     * parent extensions
     */
    this.clear = function(){ self.parent.clear(); }
    this.append = function( Menu ){ self.parent.append( Menu ); }
    this.find = function( Menu ){  return self.parent.find( Menu ); }
    /***/
    
    this.get = function(callback, $el){
        var q = ["parentID=null"];
        self.parent.get( self.fromAJAX, callback, q, $el );
    }
    
    /**
    * methods
    */
    this.menus = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function( response ){
        self.parent.nodes = self.parent.nodes.Menus;
        if ( self.parent.nodes && self.parent.nodes.length ){
            var tmp = self.parent.nodes.slice(0);
            self.clear(); 
            for (var i=0; i < tmp.length; i++){
                var Menu = new MenuTYPE(null, self.$msgBlock);
                Menu.parent.fromAJAX( tmp[i] );
                Menu.fromAJAX( tmp[i] );
                self.append(Menu);
            }
        }
    }
    
    this.mkInput = function(){
        var html = '';
        if ( self.menus() ){
            for( var i=0; i < self.menus().length; i++ ){
                menu = self.menus()[i];
                html += menu.mkInput();
            }
        }
        return html;
    }
    
    this.find = function( suspect ){ return findInList(self.menus(), suspect); }
    this.filter = function( suspect, by ){ 
        var results = new MenusTYPE();
        results.parent.nodes = filterList( self.menus(), suspect, by ); 
        return results;
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.menus(), suspect);
        if (pos>=0)
            self.menus().splice(pos, 1);
           
    }
    
    this.count = function(){ return self.menus().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}