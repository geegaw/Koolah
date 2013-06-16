/**
 * @fileOverview defines Tab
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Tab
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\elements\tabs
 * @class - handles a tabs
 * @constructor
 * @param jQuery dom object $div
 */
function TabSection( $div ){
    this.id = 'tabSection_'+( new Date().getTime() );
    this.tabs = [];
    
    this.$el = $div;
    this.$labels = null;
    this.$body = null;
    var self = this;
    
    this.labels = function(){ return self.$el.find( '.tabLabels' ); }
    this.body = function(){ return self.$el.find( '.tabsBody' ); }
    
    this.mkTabs = function(){
        var html = '';
        html+='<div id="'+self.id+'" class="tabSection">';
        html+=    '<div class="tabLabels"></div>';
        html+=    '<div class="tabsBody"></div>';
        html+='</div>';
        this.$el = $(html);
    }
    
    this.read = function(){
        self.$el.attr( 'id', this.id );
        self.$labels = self.labels();
        self.$body = self.body();
        
        self.$labels.find('.tab').each(function(){
            var tab = new Tab( $(this) );
            self.tabs[self.tabs.length] = tab;
        });
        
        self.tabs[0].mkActive();
    }
    
    this.append = function( appendee ){
        var tab;
        if ( typeof appendee =='string' ){
            tab = new Tab(); 
            tab.label = appendee    
        }
        else if ( appendee instanceof Tab ){
            tab = appendee;       
        }
        else
            return;
        self.tabs[ self.tabs.length ] = tab;
        self.$labels.append( tab.$label );
        self.$body.append( tab.$body ); 
    }
    
    this.findTabPos = function( id ){
        if ( self.tabs.length ){
            for(var i=-0; i<self.tabs.length; i++){
                var tab = self.tabs[i];
                if ( tab.id == id )
                    return i;
            }
        }
        return -1;
    }
    
    this.findTab = function( id ){
        var pos = self.findTabPos(id); 
        if (  pos >= 0 )
            return self.tabs[pos];
        return null;
    }
    
    this.findActiveTab = function(){
        var $active = self.$el.find( '.tab.active' );
        if ( $active ){
            var id = $active.find('a').attr('href').slice(1);
            if ( id )
                return self.findTab( id );
        }
        return null; 
    } 
    
    this.removeTab= function( id ){
        var pos = self.findPos( id );
        var tab = self.tabs[i];        
        tab.remove();
        self.tabs.splice( pos, 1 );
    }
    
    this.remove = function(){ self.$el.remove(); }
    
    this.refreshStyle = function(){
        var left = 0;
        var zindex = self.tabs.length;
        self.$labels.find('.tab').each(function(){
            if ( $(this).hasClass('active') ){
                $(this).css({
                    'left': left+'px',
                    'z-index': (self.tabs.length + 1) 
                });
            }
            else{
                $(this).css({
                    'left': left+'px',
                    'z-index': zindex 
                });
            }
            left-=4;
            zindex--;
        })
    }
    
    this.setHeight = function(){
        if ( self.tabs.length ){
            var max = 0;
            var curActive = self.findActiveTab();
            curActive.mkInactive();
            
            for( var i=0; i< self.tabs.length; i++ ){
                var tab = self.tabs[i];
                tab.mkActive();
                var height = self.$el.outerHeight(true);
                if ( height > max )
                    max = height;
                tab.mkInactive();                 
            }
            curActive.mkActive();
            this.$el.css('height', max+'px');
        }
    }
    
    if ( self.$el ){
        self.read();
        //self.setHeight();
        self.refreshStyle();
    }
    
    this.$el.find('.tab').live('click', function(){
        var id =  $(this).find('a').attr('href').slice(1);
        var newActive = self.findTab( id );
        var curActive = self.findActiveTab();

        curActive.mkInactive();
        newActive.mkActive();
        self.refreshStyle();
        return false;
    })

    
}
