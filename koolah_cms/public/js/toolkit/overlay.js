/**
 * @fileOverview defines Overlay
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Overlay
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\elements\tools
 * @class - handles an overlay
 * @constructor
 * @param jQuery dom object $el
 * @param string type
 * @param int fadeTime
 */
function Overlay( $el, type, fadeTime ){
    this.$el = $el;
    this.$overlay = null;
    this.openFn = null;
    this.closeFn = null;
    
    this.type = 'absolute';
    if( type )
        this.type = type;
    
    this.fadeTime = 350;
    if (fadeTime)
        this.fadeTime = fadeTime;
    
    var self = this;
    
    this.open = function( callback){
        if ( !self.$overlay ){
            self.mk();
            self.$el.append( self.$overlay );
        }
        if (!callback && self.openFn)
            callback = self.openFn;
        self.$overlay.fadeIn( self.fadeTime );
        if (callback)
        	callback();
    }
    
    this.close = function( callback ){
        if (!callback && self.closeFn)
            callback = self.closeFn;
        if (self.$overlay){
            self.$overlay.fadeOut( self.fadeTime);
            if (callback)
            	callback();	
        }
    }
    
    this.destroy = function( callback ){
        if (self.$overlay){
            self.close( function(){
                callback();
                self.$overlay.remove();     
            });
        }
    }
    
    this.mk = function(){
        self.$overlay = $('<div id="overlay" class="hide '+self.type+' zLevel"></div>' );
    }
    
    $('body').on('click', '#overlay', function(){
        self.close();
        self.closeFn();
    })
}
