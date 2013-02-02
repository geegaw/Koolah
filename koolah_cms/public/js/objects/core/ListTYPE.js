function ListTYPE(){
    this.elements = [];
    
    var self = this;
    
    this.clear = function(){ 
        self.elements = []; 
    }
    
    this.append = function( node ){ 
        self.elements[ self.count() ] = node; 
    }
    
    this.count = function(){ 
        return self.elements.length; 
    }
    
    this.list = function(){ 
        return self.elements; 
    }
     
    this.filter = function( suspect, by ){
        var results = new ListTYPE();
        
        for ( var i=0; i< self.count(); i++ ){
            var node = self.elements[i];
            if ( typeof node == 'object' ){
                if ( by == 'regex' && node.regex != undefined){
                    if ( node.regex( suspect ) ){
                         results.append( node );
                    }                         
                }
                else if( by == 'exact' && node.compare != undefined){
                    if ( node.compare( suspect ) == 'equals' )
                         results.append( node ); 
                }    
            }
            else{
                if ( by == 'regex'){
                    suspect=new RegExp( suspect );
                    if ( suspect.test( node ) )   
                        results.append( node );
                }
                else if( by == 'exact'){
                    if ( node == suspect )
                         results.append( node ); 
                }
            }
        }
        
        return results;
    }
    
    this.findPos = function( suspect ){
        var node = self.elements[i];
        for ( var i=0; i<list.length; i++ ){
            if ( typeof node == 'object' && node.compare != 'undefined'){
                if ( node.compare( suspect ) == 'equals' )
                    return i; 
            }
            else{
                if ( node == suspect )
                    return i;
            }
        }
        
        return -1;
    }
    
    this.find = function( suspect ){        
        pos = self.findPos( suspect );
        if ( pos >= 0 )
            return self.elements[pos];
       return null;
    }
    
    this.remove = function( suspect ){
        var pos = self.findPos( suspect);
        if (pos>=0)
            self.elements.splice(pos, 1);           
    }
    
    this.has = function( suspect ){ 
        return Boolean( self.findInList( suspect ) ); 
    }
    
    this.isEmpty = function(){ 
        return !Boolean(self.count() ); 
    }
}
