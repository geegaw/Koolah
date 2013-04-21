$(document).ready(function(){
    
    setTimeout( function(){
        showSlide(  $('#slides .slideInfo:first') );    
    }, ANIMATION_TIME);
    
    
    $('#thumbs a').click(function(){
        var data = $(this).data();
        var $current = $('#slides .active');
        $current.fadeOut( ANIMATION_TIME, function(){
            $(this).removeClass('active');
            showSlide( $("#slide_"+data.id) );
        })         
        return false;
    })
    
    
    function showSlide( $slide ){
        $slide.fadeIn( ANIMATION_TIME ).addClass('active');
    }
    
})