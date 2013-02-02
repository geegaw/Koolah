var koolahToolkit = {};
    
koolahToolkit.center = function( $el, $to, type, heightToo ){
    if ( !type )
        type = 'absolute';
    
    var elWidth = $el.width();
    var elHeight = $el.height();
    
    var margin_left = parseInt( elWidth / 2  ) * -1;
    
    $el.css({
        position: type,
        left: '50%',
        'margin-left': margin_left
    });
    
    if ( heightToo ){
        var toHeight = $to.height();
        var top = parseInt((toHeight - elHeight) / 2) 
    
        $el.css({
            top: top,
       }); 
    } 
    
}
