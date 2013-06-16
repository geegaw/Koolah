/**
 * @fileOverview defines RatioSizeTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RatioSizeTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\ratios
 * @class - handles data for a ratio 
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function RatioSizeTYPE( $msgBlock ) {
    
    /**
     * label - page label
     * @type LabelTYPE
     */
    this.label = new LabelTYPE();
    
    /**
     * w - width
     * @type int
     * @default 0
     */
    this.w = 0;    
    
    /**
     * h - height
     * @type int
     * @default 0
     */
    this.h = 0;
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'ratioSize'+UID();
    
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'RatioSizeTYPE'; }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.label.fromAJAX( data );
        if ( data.w )
            self.w = data.w;
        if ( data.h )
            self.h = data.h;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.w = self.w;
            tmp.h = self.h;
        return tmp;
    }
    
    /**
     * mkInput
     * - make html for ratio size 
     * @returns string
     */
    this.mkInput = function(){
        var html = '';
        return html;
    }
    
    /**
     * mkList
     * - make html list view of ratio size
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.jsID+'" class="ratioSize">';
        html+=      '<span class="name ratioSizeName">'+self.label.label+'</span>';
        html+=      '<span class="ratioSizeW">'+self.w+'</span>';
        html+=      '<span class="ratioSizeH">'+self.h+'</span>';
        html+=      '<span class="commands">';
        html+=          '<button class="edit">edit</button>';
        html+=          '<button class="del">X</button>';
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form){
        self.label.label = $.trim($('#ratioSizeName').val());
        self.w = $.trim($('#ratioSizeWidth').val());
        self.h = $.trim($('#ratioSizeHeight').val());
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        $('#ratioSizeID').val( self.jsID );
        $('#ratioSizeName').val( self.label.label );   
        $('#ratioSizeWidth').val( self.w ) 
        $('#ratioSizeHeight').val( self.h ) 
    }
    
    /**
     * compare
     * - compare two ratio sizes
     * - can expand this function to accept more
     * types, and/or return more then equals 
     * @param mixed suspect
     * @returns mixed|bool
     */
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    /**
     * regex
     * - compare two ratio sizes with regex
     * @param mixed suspect
     * @returns mixed|bool
     */
    this.regex = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                suspect=new RegExp( suspect );
                return suspect.test( self.label.label );
            default:
                return false;
                
        }
        return false;
    }
    
    /** dom actions **/   
    $('body').on( 'click', '#'+self.jsID+' .edit', function(){
        self.fillForm();
    })
    
    $('body').on('click', '#'+self.jsID+' .del', function(){
        new Comfirmation('delete').display("ratioSizeDeleteConfirm", $('#ratioSizesList'), self.label.label, self.jsID);
        return false;    
    })
    
}