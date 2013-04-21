$(document).ready(function(){
    $('.hide').hide().removeClass('hide');
    
    $('#closeSubNav').click(function(){
        var w = $('#collectionNav').outerWidth(true);
        $('#collectionNav').animate({ 
            left: '-'+w 
        }, ANIMATION_TIME);
        return false;
    })
    
    $('#collections').click(function(){
        $('#collectionNav').animate({ 
            left: 0 
        }, ANIMATION_TIME);
        return false;
    })
    
})

function center($el){
    var w = $el.width();
    $el.css( 'margin-left', (Math.floor(w /2 ) * -1)+'px' );
}
