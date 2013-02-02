function TemplateSectionTYPE(i){
    this.name = '';
    this.fields = new FieldsTYPE();
    
    this.jsID = 'section'+( new Date().getTime() )+i;
    var self = this;
    
    /**
     * parent extensions
     */
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        if ( data ){
            self.name = data.name;
            self.fields.fromAJAX( data.fields );
        }        
    }

    this.toAJAX = function(){
        var tmp = {}
            tmp.name = self.name;
            tmp.fields = self.fields.toAJAX();
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        html += '<div id="'+self.jsID+'" class="section fullWidth hide">';
        html +=     '<div class="sectionBody fullWidth">';
        html +=         '<div class="fields fullWidth"></div>';
        html +=         '<div class="newField fullWidth hide"></div>';
        html +=         '<button type="button" class="addField center">Add New Field</button>';
        html +=         '<a href="#" class="delSection">delete section</a>';
        html +=     '</div>';
        html += '</div>';
        return html;
    }
    
    this.mkTab = function(){
        var html = '<div class="tab"><a href="'+self.jsID+'">'+self.name+'</a></div>';
        return html;
    }
    
    this.readForm = function( $form ){
        self.name = $form.find('a').html();
        self.fields.readForm( $form );
        return self;
    }
    
    this.fillForm = function(){        $('#addSection').before( self.mkTab() );        $('#sections').append( self.mkInput() );        self.fields.fillForm( $('#'+self.jsID) );
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
    /***/
    
}