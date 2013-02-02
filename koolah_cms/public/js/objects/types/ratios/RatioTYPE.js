function RatioTYPE( $msgBlock ) {
    this.parent = new Node( 'KoolahRatio' );
    this.label = new LabelTYPE();
    this.w = 0;    
    this.h = 0;
    this.sizes = new RatioSizesTYPE( $msgBlock );
    
    this.$msgBlock = $msgBlock;
    
    
    this.jsID = 'ratio'+UID();
    
    var self = this;
    
    /**
     * parent extensions
     */
    this.save = function( callback, $el ){ 
        console.log( self.toAJAX() )
        
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.save( self.toAJAX(), null,  callback, $el );
    }
    this.get = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, $el, aysnc ); 
     }    
    this.del = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.del(null, callback, $el, aysnc ); 
    }
    this.getID = function(){ return self.parent.getID(); }
    this.equals = function( ratio ){ return self.parent.equals( ratio ); }
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );
        self.label.fromAJAX( data );
        if ( data.w )
            self.w = data.w;
        if ( data.h )
            self.h = data.h;
        if (data.sizes)
            self.sizes.fromAJAX( data.sizes );
    }

    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.w = self.w;
            tmp.h = self.h;
            tmp.sizes = self.sizes.toAJAX();
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="ratio" data-id="'+self.parent.id+'">';
        html+=      '<span class="name ratioName">'+self.label.label+'</span>';
        html+=      '<span class="commands">';
        html+=          '<button class="edit">edit</button>';
        html+=          '<button class="del">X</button>';
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    this.readForm = function( $form){
        if ($.trim($('#ratioID').val()).length )
            self.parent.id = $.trim( $('#ratioID').val() );
        self.label.label = $.trim($('#ratioName').val());
        self.w = $.trim($('#ratioWidth').val());
        self.h = $.trim($('#ratioHeight').val());
        //self.sizes.readForm();
        return self;
    }
    
    this.fillForm = function(){
        if (self.parent.id)
            $('#ratioID').val( self.parent.id );
        $('#ratioName').val( self.label.label );   
        $('#ratioWidth').val( self.w ) 
        $('#ratioHeight').val( self.h ) 
        $('#ratioSizesList ul').html( self.sizes.mkList() );
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
       
    $('body').on( 'click', '#'+self.jsID+' .edit', function(){
        self.fillForm();
    })
    
    $('body').on('click', '#'+self.jsID+' .del', function(){
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#ratios'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm', function(){
        self.del( null, self.$msgBlock, false );
    })
    
}