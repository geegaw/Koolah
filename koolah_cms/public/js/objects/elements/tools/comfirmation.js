/**
 * @fileOverview defines Comfirmation
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Comfirmation
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\elements\tools
 * @class - handles a comfirmation message
 * @constructor
 * @param string type
 */
function Comfirmation(type){
    this.type = type;
    if (!this.type)
        this.type = 'basic';
        
    var self = this;
        
    this.display = function(htmlID, $el, msg, id){
        switch( self.type ){
            case 'delete': 
                self.displayDeleteConfirmation(htmlID, $el, msg, id);
                break;
            default:
                self.displayConfirmation(htmlID, $el, id)
                break;
        }
    }

    this.displayDeleteConfirmation = function(htmlID, $el, toDelete, id){
        self.displayConfirmationMsg(htmlID, $el, 'Are you sure you want to delete '+toDelete+'?', 'YES Delete', "NO Don't Delete", id);
    }
    
    this.displayConfirmation = function(htmlID, $el, id){
        self.displayConfirmationMsg(htmlID, $el, 'Are you sure?', 'YES', 'NO', id);
    }
    
    this.displayConfirmationMsg = function(htmlID, $el, msg, yes, no, id){
        $('#overlay').remove();
        $('#overlayBox').remove();
        
        var dataID = '';
        if ( id )
            dataID = 'data-id="'+id+'"';
            
        var html =''+
        '<div id="overlay"></div>'+
        '<div id="overlayBox" class="removableBody">'+
            '<div class="top commandBar"><a href="#" class="removable">X</a></div>'+
            '<div class="msg">'+msg+'</div>'+
            '<div class="options">'+
                '<button class="yes" id="'+htmlID+'" '+dataID+'>'+yes+'</button>'+
                '<button class="no">'+no+'</button>'+
            '</div>'+
        '</div>';
        $el.append(html);
    }
    
    $('.no, .yes').live( 'click', function(){
        closeOverlay();
    });
    
    $('.removable').live({
        click: function(){
            closeOverlay();    
        }
    })

}

