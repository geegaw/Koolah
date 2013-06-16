/**
 * @fileOverview defines Nav
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Nav
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\elements\nav
 * @class - works with nav elemetns
 * @constructor
 * @param jQuery dom object $el
 */
function Nav( $el ){
    
    /**
     * $el - dom reference to nav element
     *  @type jQuery dom object
     */
    this.$el = $el;
    
    /**
     * openFn - function to call upon open
     * @type string
     */
    this.openFn = null;
    
    /**
     * closeFn - function to call upon close
     * @type string
     */
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
