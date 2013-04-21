function TemplateTYPE(){
    this.parent = new Node( 'KoolahTemplate' );
    this.label = new LabelTYPE();
    this.sections = new TemplateSectionsTYPE();    
    this.templateType = '';
    var self = this;
    
    /**
     * parent extensions
     */
    this.save = function( callback, $el ){ self.parent.save( self.toAJAX(), null,  callback, $el );}
    this.get = function( callback, $el ){ self.parent.get( self.fromAJAX, callback, $el ); }    
    this.del = function( callback, $el ){ self.parent.del(null, callback, $el ); }
    this.getID = function(){ return self.parent.getID(); }
    this.equals = function( template ){ return self.parent.equals( template ); }
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.label.fromAJAX( data );
        self.sections.fromAJAX( data.sections );
        self.templateType = data.templateType;
    }

    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.sections = self.sections.toAJAX();
            tmp.templateType = self.templateType;
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.parent.id+'" class="template fullWidth">';
        html+=      '<span class="templateName">'+self.label.label+'</span>';
        html+=      '<span class="commands">';
        html+=          '<a class="edit" href="template/?templateType='+self.templateType+'&templateID='+self.parent.id+'" >edit</a>';
        html+=          '<a class="del" href="'+self.parent.id+'" >X</a>';
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    this.readForm = function( $form){
        self.parent.id = $('#templateID').val();
        self.templateType = $('#templateType').val();
        
        self.label.label = $.trim( $('#templateName').val() );
        self.label.ref = $.trim( $('#templateNameRef').val() );
        
        if( self.templateType == 'field' ){
            var general = new TemplateSectionTYPE();
            general.name = 'general';
            self.sections.append( general );
        }
        else
            self.sections.readForm( $form );
        return self;
    }
    
    this.fillForm = function(){
        $('#templateName').val( self.label.label );    
        self.sections.fillForm();
    }
    
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.parent.id) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    this.regex = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                suspect=new RegExp( suspect );
                return suspect.test( self.label.label );
            default:
                return false;
                
        }
        return false;
    }
       
    /***
     *
     *  
     * NOTE: if adding types also must add in TemplateTYPE.php 
     */
    this.getTypes = function(){
        var types = [
                    'page', 
                    'widget',
                    'field'
                ];
        return types;
    }    
    /***/
    
    
    this.getAllFields = function(){
        var fields = [];
        var sections = self.sections.sections();
        for (var i = 0; i < sections.length; i++){
            var section = sections[i];
            var sectionFields = section.fields.fields;
            fields = fields.concat( sectionFields );
        }
        return fields;
    }
}