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
 * @class - handles a tab
 * @constructor
 * @param jQuery dom object $div
 */
function Tab( $div ){
    this.id = 'tab_'+( new Date().getTime() );
    this.label = '';
    this.$label = '';
    this.$body = '';
    
    var self = this;
    
    this.init = function(){
        self.$label = self.mkLabel();
        self.$body = self.mkBody();
    }
        
    this.mkLabel = function(suplemantalClasses){
        self.$label = $('<div class="tab '+suplemantalClasses+'"><a href="#'+self.id+'">'+self.label+'</a></div>');
    }
    this.mkBody = function(suplemantalClasses){
        self.$body = $('<div id="'+self.id+'" class="tabBody hide '+suplemantalClasses+'"></div>');
    }
    
    this.read = function( $div ){
       self.$label = $div;
       self.label = self.$label.find('a').html();
       self.id = $div.find('a').attr('href').slice(1);
       self.$body = $('#'+self.id);
    }
    
    this.remove = function(){
        self.$label.remove();
        self.$body.remove();
    }
    
    this.mkActive = function(){
        self.$label.addClass('active');
        self.$body.show();
    }
    this.mkInactive = function(){
        self.$label.removeClass('active');
        self.$body.hide();
    }
    
    
    if ( $div )
        self.read( $div );
    else
        self.init();
    
}
