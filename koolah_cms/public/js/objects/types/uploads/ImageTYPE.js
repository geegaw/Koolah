function ImageTYPE( $msgBlock ) {
    this.parent = new Node( 'KoolahImage' );
    this.file = new FileTYPE( $msgBlock );
    this.ratio = new RatioTYPE( $msgBlock );
    this.crop = new CropTYPE();
    
    this.$msgBlock = $msgBlock;
    
    this.jsID = 'file'+UID();    
    var self = this;
    
    /**
     * parent extensions
     */
    this.save = function( callback, $el ){ 
        if (!$el)
            $el = self.$msgBlock;
        self.parent.save( self.toAJAX(), null,  callback, $el );
    }
    this.get = function( callback, $el, async ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, $el, async ); 
     }    
    this.del = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.del(null, callback, $el, aysnc ); 
    }
    this.getID = function(){ return self.parent.getID(); }
    this.equals = function( file ){ return self.parent.equals( file ); }
    /***/

    /**
     * methods
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );        
        self.file.parent.id = data.file;
        self.ratio.parent.id = data.ratio;
        self.crop.fromAJAX( data.crop )
    }

    this.toAJAX = function(){
        var tmp = {};
        tmp.ratio = self.ratio.parent.id;
        tmp.file = self.file.parent.id;
        tmp.crop = self.crop.toAJAX();
        return tmp;
    }
    
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    this.mkList = function( ratios ){
        var html = '';
        return html;
    }
    
    
    this.readForm = function(){
        self.crop.readForm(); 
        if ( $('#cropRatioID').val() ) 
            self.ratio.parent.id = $('#cropRatioID').val();
        return self;
    }
    

    this.fillForm = function(){
        var coords = [ 0, 0, 150, 150 ];
        if ( self.crop && self.crop.coords )
            coords = self.crop.coordsToArray();
        
        $('#cropImg')
            .attr('src',  UPLOADS_PATH+self.file.filename )
            .Jcrop({
                            bgColor:     '#000',
                            bgOpacity:   .25,
                            setSelect:  coords
                        },
                        function(){ 
                            self.crop.cropObj = this;
                            $('#cropImgHeight').css('height', $('#cropImg').height() );
                            $('#cropImgHeight span').html( $('#cropImg').height()+'px' )
                            $('#cropImgWidth').css('width', $('#cropImg').width() );;
                            $('#cropImgWidth span').html( $('#cropImg').width()+'px' )
                            koolahToolkit.center( $('#cropSection'), $(window), 'absolute' ); 
                        }
                     );
               
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
}