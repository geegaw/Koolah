function RatioSizeTYPE( $msgBlock ) {
    this.label = new LabelTYPE();
    this.w = 0;    
    this.h = 0;
    
    
    this.jsID = 'ratioSize'+UID();
    
    var self = this;
    
    
    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.label.fromAJAX( data );
        if ( data.w )
            self.w = data.w;
        if ( data.h )
            self.h = data.h;
    }

    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.w = self.w;
            tmp.h = self.h;
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="ratioSize">';
        html+=      '<span class="name ratioSizeName">'+self.label.label+'</span>';
        html+=      '<span class="ratioSizeW">'+self.w+'</span>';
        html+=      '<span class="ratioSizeH">'+self.h+'</span>';
        html+=      '<span class="commands">';
        html+=          '<button class="edit">edit</button>';
        html+=          '<button class="del">X</button>';
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    this.readForm = function( $form){
        self.label.label = $.trim($('#ratioSizeName').val());
        self.w = $.trim($('#ratioSizeWidth').val());
        self.h = $.trim($('#ratioSizeHeight').val());
        return self;
    }
    
    this.fillForm = function(){
        $('#ratioSizeID').val( self.jsID );
        $('#ratioSizeName').val( self.label.label );   
        $('#ratioSizeWidth').val( self.w ) 
        $('#ratioSizeHeight').val( self.h ) 
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
       
    $('body').on( 'click', '#'+self.jsID+' .edit', function(){
        self.fillForm();
    })
    
    $('body').on('click', '#'+self.jsID+' .del', function(){
        new Comfirmation('delete').display("ratioSizeDeleteConfirm", $('#ratioSizesList'), self.label.label, self.jsID);
        return false;    
    })
    
}