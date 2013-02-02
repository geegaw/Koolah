function dataPoint(){
    this.ref = '';
    this.data = '';
    
    var self = this;
    
    this.fromAJAX = function(data) {
        self.ref = data.ref;
        self.data = data.data;
    }

    this.toAJAX = function() {
        var tmp = {};
        tmp.ref = self.ref;
        tmp.data = self.data;
        return tmp;
    }
    
    this.readBlock = function( $block ){
        self.data = {};
        $block.find('> .field').each(function(){
            var data = new dataPoint();
            data.read( $(this) );
            if ( data.data )
                self.data[ data.ref ] = data.data; 
        })
    }
    
    this.read = function($field){
        if ( $field.hasClass('many') )
            self.readMany( $field );
        else if ( $field.hasClass('custom') || $field.find('.custom').length )
            self.readCustom( $field );
        else if( $field.hasClass('fileField'))
            self.readFile( $field );
        //else if( $field.hasClass('dateField'))
        //    self.readDate( $field );    
        else{
            var $label = $field.find('label');
            var $input = $label.next();
            self.readInput( $input );
        }
    }
    
    this.readCustom = function($field){
        self.ref = $field.find('> .customRef').val();
        self.data = {};
//console.log('reading custom =>'+ self.ref)
//console.log($field)
        $field.find('> .field').each(function(){
            var data = new dataPoint();
            data.read( $(this) );
            if ( data.data )
                self.data[ data.ref ] = data.data;    
        })
//console.log(self.data)        
    }
    
    this.readMany = function($field){
        self.ref = $field.find('> .manyRef').val();
        self.data = [];
//console.log('reading many =>'+ self.ref)        
        $field.find('> .manyBody > .collapsibleBody').each(function(){
            $(this).find('.field')
            var data = new dataPoint();
            data.readBlock( $(this) );
            if ( data.data )
                self.data[ self.data.length ] = data.data;    
        });
//console.log(self.data)        
    }
    
    this.readFile = function($field){
        var data = $field.data();
        self.ref = data.ref;
        self.data = $field.find('.fileID').val();
//console.log(self.data)        
    }
    
    this.readDate = function($field){
        var data = $field.data();
        self.ref = data.ref;
        self.data = $field.find('.fileID').val();
//console.log(self.data)        
    }
    this.readInput = function($input){
        self.ref = $input.attr('id');
        self.data = $input.val();
        if ( self.data == 'no_selection' )
            self.data = null;
//console.log('reading simple =>'+ self.ref)        
    }

}
