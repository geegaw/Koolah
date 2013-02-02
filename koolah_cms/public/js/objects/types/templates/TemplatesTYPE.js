function TemplatesTYPE(){
    this.parent = new Nodes( 'KoolahTemplates' );
    var self = this;

    /**
     * parent extensions
     */
    this.get = function( callback, args, $el ){ self.parent.get( self.fromAJAX, callback, args, $el ); }
    this.clear = function(){ self.parent.clear(); }
    this.append = function( template ){ self.parent.append( template ); }
    this.find = function( template ){  return self.parent.find( template ); }
    /***/
    
    /**
    * methods
    */
    this.templates = function(){ return self.parent.nodes; }
    
    this.fromAJAX = function( response ){
        if ( self.parent.nodes && self.parent.nodes.length ){
            var tmp = self.parent.nodes.slice(0);
            self.clear(); 
            for (var i=0; i < tmp.length; i++){
                var node = tmp[i];
                var template = new TemplateTYPE();
                template.parent.fromAJAX( node );
                template.fromAJAX( node );
                self.append(template);
            }
        }
    }
    
    this.sort = function(){
        for (var i=0; i < self.templates().length; i++){
            var template = self.templates()[i];
            if (template.templateType){           
                if(!self[ template.templateType ])
                    self[ template.templateType ] = [];
                self[ template.templateType ][ self[ template.templateType ].length ] = template;
             }
        }    
    }
    
    this.find = function( suspect ){ return findInList(self.templates(), suspect); }
    this.filter = function( suspect, by ){ 
        var results = new TemplatesTYPE();
        results.parent.nodes = filterList( self.templates(), suspect, by ); 
        return results;
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.templates(), suspect);
        if (pos>=0)
            self.templates().splice(pos, 1);
           
    }
    
    this.count = function(){ return self.templates().length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}