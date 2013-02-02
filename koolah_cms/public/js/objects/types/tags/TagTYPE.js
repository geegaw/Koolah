function TagTYPE( $msgBlock ) {
    this.parent = new Node( 'KoolahTag' );
    this.label = new LabelTYPE();
    
    this.$msgBlock = $msgBlock;
    
    
    this.jsID = 'tag'+UID();
    
    var self = this;
    
    /**
     * parent extensions
     */
    this.save = function( callback, $el ){ 
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
    this.equals = function( tag ){ return self.parent.equals( tag ); }
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );
        self.label.fromAJAX( data );
    }

    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="tag fullWidth">';
        html+=      '<span class="name tagName">'+self.label.label+'</span>';
        html+=      '<span class="commands">';
        html+=          '<button class="edit">edit</button>';
        html+=          '<button class="del">X</button>';
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    this.readForm = function( $form){
        if ( $('#tagID').val() )
               self.parent.id = $('#tagID').val(); 
        self.label.label = $.trim($('#tagName').val());
        return self;
    }
    
    this.fillForm = function(){
        if (self.parent.id)
            $('#tagID').val( self.parent.id );
        $('#tagName').val( self.label.label );    
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
                suspect=new RegExp( suspect, 'i' );
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
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#tagList'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm', function(){
        self.del( null, self.$msgBlock, false );
    })
}