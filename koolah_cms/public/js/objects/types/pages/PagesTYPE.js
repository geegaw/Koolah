function PagesTYPE($el) {
    this.parent = new Nodes('KoolahPages');

    this.$el = $el;
    var self = this;

    /**
     * parent extensions
     */
    this.get = function( callback, args, $el, async ){ self.parent.get( self.fromAJAX, callback, args, $el, async ); }
    this.clear = function(){ self.parent.clear(); }
    this.append = function( page ){ self.parent.append( page ); }
    /***/

    /**
     * methods
     */
    this.pages = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function(data) {
        self.clear();
        if (data && data.nodes && data.nodes.length){
            for(var i=0; i < data.nodes.length; i++ ){
                var page =  new PageTYPE();
                page.fromAJAX( data.nodes[i] )
                self.append( page );
            }
        }
        
    }

    this.toAJAX = function() {
        var tmp = [];
        if (self.count()){
            for(var i=0; i < self.count(); i++ ){
                var page =  self.pages()[i];
                tmp[tmp.length]= page.toAJAX();
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
            self.$el = $form.find('.pages');
        
        self.clear();
        self.$el.find('.page').each(function(){
            var page = new PageTYPE();
            page.jsID = $(this).attr('id');
            page.readForm();
            self.parent.append( page );           
        })
    }

    this.fillForm = function() {
        if ( self.count() ){
            for( var i=0; i< self.count(); i++  ){
                var page = self.pages()[i];
                self.appendToPage( page );
            }
        }
        self.mkSortable();
    }
    
    this.mkSortable = function(){    
        self.$el.find('.pagesList ').sortable({
            items: '.page',
            update: self.readForm
        });
    }
    
    this.mkList = function( params, tagParams ){
        var html = '';
        if ( self.pages() ){
            for (var i=0; i < self.pages().length; i++){
                var page = self.pages()[i];
                html += page.mkList( params, tagParams );   
            }
        }
        return html;
    }
    
    this.find = function( suspect ){ return findInList(self.pages(), suspect); }
    this.filter = function( suspect, by ){ 
        //var results = new TemplatesTYPE();
        //results.parent.nodes = filterList( self.templates(), suspect, by ); 
        //return results;
    }
    this.remove = function( suspect ){
        var pos = findPosInList(self.pages(), suspect);
        if (pos>=0)
            self.pages().splice(pos, 1);
        console.log( pos )
    }
    
    this.count = function(){ return self.pages().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}