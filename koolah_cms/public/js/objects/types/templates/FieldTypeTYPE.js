function FieldTypeTYPE(){
    this.type = '';
    this.options = '';
    
    var self = this;

    /**
     * parent extensions
     */
    /***/
    
    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.type = data.type;
        self.options = data.options;        
    }

    this.toAJAX = function(){
        var tmp = {}
            tmp.type = self.type;
            tmp.options = self.options;
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.readForm = function( $form){
    }
    
    this.fillForm = function(){
    }
    
    this.getTypes = function(){
        return TEMPLATE_FIELD_TYPES;
    }
    /***/

}