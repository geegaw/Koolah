$(document).ready(function(){

    $('.command a').click(function(){
        var $this = $(this);
        var showClass = $this.attr('class');
        
        if ( !showClass )
            return true;
        
        $('.command div').fadeOut( 350 );
        $('.command div:last').fadeOut( 350, function(){
            $('.command .'+showClass).fadeIn( 350 );
            centerEls();
        });
        return false;
    });    
    
});
