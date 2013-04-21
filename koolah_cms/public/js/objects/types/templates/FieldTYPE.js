function FieldTYPE(){
    this.required = false;
    this.many = false;
    this.label = new LabelTYPE();
    
    //this.type = new FieldTypeTYPE();
    this.options = '';
    this.type = '';
    
    this.jsID = 'field'+UID(); 
    var self = this;

    /**
     * parent extensions
     */
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        if (data){
            self.required = data.required;
            self.many = data.many;
            self.options = data.options;
            self.label.fromAJAX( data );
            //self.type.fromAJAX( data.type );
            self.type = data.type;
        }
    }

    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.required = self.required;
            tmp.many = self.many;
            tmp.options = self.options;
            //tmp.type = self.type.toAJAX();
            tmp.type = self.type;
        return tmp;
    }
    
    this.mkCapsule = function(){
        var html = ''+ 
        '<div id="'+ self.jsID +'" class="field fullWidth">'+
        '   <div class="fieldInfo">'+
        '       <div class="fieldNameInfo">'+
        '           <div class="fieldName fullWidth">'+self.label.label+'</div>'+
        '           <div class="fieldSub fullWidth">';
        if ( self.required )
            html+= '    <div class="isRequired">Required</div>';
        if ( self.many )
            html+= '    <div class="many">can be many</div>';
        html += ''+
        '           </div>'+
        '       </div>'+
        '       <div class="fieldType">'+self.type+'</div>'+
        '       <div class="hide fieldOptions">'+self.options+'</div>'+
        '   </div>'+
        '   <div class="fieldInfoCommands">'+
        '       <button class="editField">edit</button>'+
        '       <button class="delField">X</button>'+
        '   </div>'+
        '</div>';
        return html;
    }
    
    this.readForm = function( $form ){
        /*
        self.name = $form.find('.fieldName').html();
        self.type = $form.find('.fieldType').html();
        self.required = $form.find('.isRequired').length;
        self.many = $form.find('.many').length; 
        self.options = $form.find('.fieldOptions').html();
        */
        self.label.label = $.trim( $('#newFieldName').val() );
        self.label.ref = $.trim( $('#newFieldNameRef').val() );
        self.type = $('#fieldType').val();
        switch(self.type){
            case 'custom':
                self.options = $('#template').val();
                break;
            case'dropdown':
                self.options = $('#dropdownOptions').val();
                break;
            case 'file':
                self.options = $('#fileTypeSelect').val();
                break;
            case 'query':
                var query = new QueryTYPE();
                query.readForm();
                self.options = query.toAJAX();  
                break;
            default:
                break;
        }
        
        self.required = $('#isRequired').is(':checked');
        self.many = $('#many').is(':checked');
        return self;
    }
    
    this.fillForm = function(){
        $('#newFieldName').val( self.label.label );
        $('#newFieldNameRef').val( self.label.ref );
        $('#fieldType option[value="'+self.type+'"]').attr('selected', 'selected');
        
        switch( self.type ){
            case 'custom':
                $('#template option[value="'+self.options+'"]').attr('selected', 'selected');
                $('#custom').show();
                break
            case 'dropdown':
                $('#dropdownOptions').val(self.options);
                $('#dropdown').show();
                break;
            case 'file':
                $('#fileTypeSelect option[value="'+self.options+'"]').attr('selected', 'selected');
                $('#fileType').show();
                break
            case 'query':
                var query = new QueryTYPE();
                query.fromAJAX( self.options );
                query.fillForm();  
                $('#queryType').show();
                break;
            default:
                break;
        }
        
        if ( self.required )
            $('#isRequired').attr('checked', 'checked');
        
        if ( self.many )
            $('#many').attr('checked', 'checked');
    }
    
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
    }
    /***/
    
}