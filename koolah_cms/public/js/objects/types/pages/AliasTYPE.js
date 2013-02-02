function AliasTYPE($msgBlock){
    this.parent = new Node('KoolahAlias');

    this.alias = '';
    
    this.$msgBlock = $msgBlock;
    this.jsID = 'alias'+( new Date().getTime() ); 
    
    var aliasDisplayParams = { 'editable': true };
    var self = this;

    /**
     * parent extensions
     */
    this.save = function(callback, $el) { self.parent.save(self.toAJAX(), null, callback, $el); }
    this.get = function(callback, $el, async) { self.parent.get(self.fromAJAX, callback, $el, async); }
    this.del = function(callback, $el) { self.parent.del(null, callback, $el); }
    this.getID = function() { return self.parent.getID(); }
    this.equals = function(alias) { return self.parent.equals(alias); }
    /***/

    /**
     * methods
     */
    this.fromAJAX = function(data){
        self.parent.id = data.id
        self.alias = data.alias;
    }

    this.toAJAX = function(){
        var tmp = {};
        tmp.alias = self.alias;
        tmp.id = self.parent.id;
        return tmp;
    }
    
    this.mkPod = function(){
        var pod = new Pod( self.jsID );
        pod.id = self.parent.id;
        pod.label = self.alias;
        return pod.mk( 'alias', aliasDisplayParams );
    }
    
    this.mkCapsule = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="alias">';
        html+=    '<span class="aliasName">'+self.alias+'</span>';
        html+=    '<a href="#" class="del">X</a>'; 
        html+= '</li>';  
        return html;
    }

    this.readForm = function(){
        var pod = new Pod( self.jsID );
        pod.read();
        self.alias = pod.label;
        self.parent.id = pod.id;
    }

    this.fillForm = function($alias){
        $alias.find('label').attr('for', self.jsID);
        $alias.find('input').attr('id', self.jsID).val( self.alias );
    }
    
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    this.isUnique = function(alias){
        if (!alias)
            alias = self.alias;
        var q = ["alias="+alias];
        var tester = new AliasesTYPE( self.$msgBlock );
        tester.get(null, q, self.$msgBlock, false);
        return tester.empty();    
    }
    
    this.mkSafe = function(){
        self.alias = self.alias.replace(/ /g, '-');
        self.alias = self.alias.replace(/[^0-9a-zA-Z-\/]/g, '');
        self.alis = self.alias.toLowerCase();
    }
    /***/
    
}