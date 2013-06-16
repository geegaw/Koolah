/**
 * @fileOverview defines CropTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * CropTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\uploads
 * @class - handles data for a crop using jcrop
 * @constructor
 * @see <a href="http://deepliquid.com/content/Jcrop.html">jcrop</a>
 */
function CropTYPE(){
    
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
     * coords - coordinates of crop from jcrop
     * @type array
     * @default null
     */
    this.coords = null;
    
    /**
     * cropObj - pointer to jcrop object 
     * @type object
     * @default null
     */
    this.cropObj = null;
    
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'CropTYPE'; }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.w = data.w;
        self.h = data.h;
        self.coords = data.coords;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {};
            tmp.w = self.w;
            tmp.h = self.h;
            tmp.coords = self.coords;
        return tmp;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form){
        self.coords = self.cropObj.tellSelect();
        
        self.w = parseInt( $('#width').val() );
        self.h = parseInt( $('#heigth').val() );
        
        if ( !self.w || !self.h){
            self.w = self.coords.w;
            self.h = self.coords.h;
        }
        
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        $('#width').val( self.w );
        $('#height').val( self.h );
    }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return ( !self.w || !self.h ); }   
    
    /**
     * coordsToArray
     * - converts coords object to array
     * @returns array
     */
    this.coordsToArray = function(){
        arr = [];
        if ( self.coords )
            arr = [ self.coords.x, self.coords.y, self.coords.x2, self.coords.y2 ]; 
        return arr;
    }
    
}