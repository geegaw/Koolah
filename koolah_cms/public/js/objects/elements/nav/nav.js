function Nav( $el ){
    this.$el = $el;
    
    this.openFn = null;
    this.closeFn = null;
    
    var self = this;
    
    $('body').on('click', self.$el + ' .subMenuTrigger', function(){
        var $subNav = $(this).parents('.menuItem:first').find('.subMenu');
        if ( $subNav.is(':visible') )
             $subNav.animate({ left: '0' }, NAV_DURATOIN, function(){ $(this).hide() } );
        else
            $subNav.show().animate({ left: '150px'  }, NAV_DURATOIN );   
    })
    
    function openSubnav($el){ $el.show().animate({ left: '150px'  }, NAV_DURATOIN ); }
    function closeSubnav($el, callback){ $el.animate({ left: '0' }, NAV_DURATOIN, function(){ $(this).hide(); callback(); } ); }
}
