function ConditionsTYPE(){
    this.conditions = new ListTYPE();    
    var self = this;

    /**
    * methods
    */
   this.els = function(){ return self.conditions.elements; }
   
    this.fromAJAX = function( response ){
        self.clear();
        if ( response && response.length ){
             for( var i=0; i < response.length; i++ ){
                 var data = response[i];
                 var condition = new ConditionTYPE();
                 condition.fromAJAX( data );
                 self.append( condition );
            }   
        }
    }
    
    this.toAJAX = function(){
        var tmp = [];
        if (self.conditions && !self.isEmpty() ){
            for( var i=0; i < self.count(); i++ )
                tmp[i] = self.conditions.elements[i].toAJAX();
        }    
        return tmp;    
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        return html;
    }
    /***/
    
    
    this.clear = function(){ self.conditions.clear(); }
    this.append = function( condition ){ this.conditions.append( condition ); }
    this.find = function( condition ){  return self.conditions.find( condition ); }
    this.list = function(){ return self.conditions.list(); }
    this.find = function( suspect ){ return self.conditions.find( suspect); }
    this.filter = function( suspect, by ){ return self.conditions.filter( suspect, by ); }
    this.remove = function( suspect ){ self.conditions.remove( suspect ); }    
    this.count = function(){ return self.conditions.count(); }
    this.isEmpty = function(){ return self.conditions.isEmpty(); }
    /***/

}