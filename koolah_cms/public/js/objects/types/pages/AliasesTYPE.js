function AliasesTYPE($el) {
    this.parent = new Nodes('KoolahAliases');

    this.$el = $el;
    var self = this;

    /**
     * parent extensions
     */
    this.get = function( callback, args, $el, async ){ self.parent.get( self.fromAJAX, callback, args, $el, async ); }
    this.clear = function(){ self.parent.clear(); }
    this.append = function( alias ){ self.parent.append( alias ); }
    
    this.appendToPage = function( alias ){
        self.$el.find('.aliasesList').append( alias.mkPod() );
        self.mkSortable();
    }
    
    /***/

    /**
     * methods
     */
    this.aliases = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function(data) {
//console.log( data );        
        if (data && data.aliases && data.aliases.length){
            for(var i=0; i < data.aliases.length; i++ ){
                var alias =  new AliasTYPE();
                alias = alias.fromAJAX( data.aliases[i] )
                self.append( alias );
            }
        }
    }

    this.toAJAX = function() {
        var tmp = [];
        if (self.count()){
            for(var i=0; i < self.count(); i++ ){
                var alias =  self.aliases()[i];
                tmp[tmp.length]= alias.toAJAX();
            }
            
        }
        return tmp;
    }

    this.mkInput = function() {
        var html = '';
        return html;
    }

    this.readForm = function($form) {
        if ( $form && !self.$el )
            self.$el = $form.find('.aliases');
        
        self.clear();
        self.$el.find('.alias').each(function(){
            var alias = new AliasTYPE();
            alias.jsID = $(this).attr('id');
            alias.readForm();
            self.parent.append( alias );           
        })
    }

    this.fillForm = function() {
        if ( self.count() ){
            for( var i=0; i< self.count(); i++  ){
                var alias = self.aliases()[i];
                self.appendToPage( alias );
            }
        }
        self.mkSortable();
    }
    
    this.mkSortable = function(){    
        self.$el.find('.aliasesList ').sortable({
            items: '.alias',
            update: self.readForm
        });
    }
    
    
    this.find = function( suspect ){ return findInList(self.aliases(), suspect); }
    this.filter = function( suspect, by ){ 
        //var results = new TemplatesTYPE();
        //results.parent.nodes = filterList( self.templates(), suspect, by ); 
        //return results;
    }
    this.remove = function( suspect ){
        var pos = findPosInList(self.aliases(), suspect);
        if (pos>=0)
            self.aliases().splice(pos, 1);
        console.log( pos )
    }
    
    this.count = function(){ return self.aliases().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}