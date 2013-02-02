function FieldsTYPE() {
    this.fields = [];
    var self = this;

    /**
     * parent extensions
     */
    /***/

    /**
     * methods
     */
    this.clear = function() {
        self.fields = [];
    }

    this.append = function(field) {
        if ( field instanceof FieldTYPE )
            self.fields[self.fields.length] = field;
        else {
            var tmp = new FieldTYPE();
            tmp.fromAJAX(field);
            self.fields[self.fields.length] = tmp;
        }
    }

    this.fromAJAX = function(fields) {
        self.clear();
        if (fields && fields.length) {
            for (var i = 0; i < fields.length; i++) {
                var field = new FieldTYPE();
                field.fromAJAX( fields[i] );
                self.append(field);
            }
        }
    }

    this.toAJAX = function(){
        var tmp = [];
        if (self.fields && !self.empty() ){
            for( var i=0; i< self.count(); i++ )
                tmp[i]=self.fields[i].toAJAX();
        }    
        return tmp;    
    }

    
    this.readForm = function( $form ){
        self.clear();
        $form.find('.field').each(function(){
            var field = new FieldTYPE();
            self.append( field.readForm($(this)) );        
        });
    }
    
    this.fillForm = function( $section ){
        if ( self.fields && self.fields.length ){
            for( var i=0; i <  self.fields.length; i++)
                $section.find('.fields').append( self.fields[i].mkCapsule() )
        }
    }
    
    this.update = function( $form ){
        var fields = [];
        $form.find('.field').each(function(){
            var $this = $(this);
            var field = self.find( $this.attr('id') );
            fields[fields.length] = field;
        });
        self.fields = fields;
        return self;
    }
    
    this.find = function( suspect ){
console.log( self )        
       return findInList(self.fields, suspect); 
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.fields, suspect);
        if (pos>=0)
            self.fields.splice(pos, 1);
    }

    this.count = function(){ return self.fields.length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}