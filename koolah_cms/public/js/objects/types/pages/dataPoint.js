/**
 * @fileOverview defines dataPoint
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * dataPoint
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @class - a data entry to a page
 * @constructor
 */
function dataPoint(){
    
    /**
     * ref - reference
     * @type string
     * @default ''
     */
    this.ref = '';
    
    /**
     * data - data
     * @type string
     * @default ''
     */
    this.data = '';
    
    var self = this;
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function(data) {
        self.ref = data.ref;
        self.data = data.data;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function() {
        var tmp = {};
        tmp.ref = self.ref;
        tmp.data = self.data;
        return tmp;
    }
    
    /**
     * readBlock
     * - read data from a block and fill in data 
     * @param jQuery dom object $block - block to read from
     */
    this.readBlock = function( $block ){
        self.data = {};
        $block.find('> .field').each(function(){
            var data = new dataPoint();
            data.read( $(this) );
            if ( data.data )
                self.data[ data.ref ] = data.data; 
        })
    }
    
    /**
     * read
     * - read data from a field and fill in data 
     * - call appropriate read based on type
     * @param jQuery dom object $field - field to read from
     */
    this.read = function($field){
        if ( $field.hasClass('many') )
            self.readMany( $field );
        else if ( $field.hasClass('custom') || $field.find('.custom').length )
            self.readCustom( $field );
        else if( $field.hasClass('fileField'))
            self.readFile( $field );
        //else if( $field.hasClass('dateField'))
        //    self.readDate( $field );    
        else{
            var $label = $field.find('label');
            var $input = $label.next();
            self.readInput( $input );
        }
    }
    
    /**
     * readCustom
     * - read a custom field and fill in data 
     * @param jQuery dom object $field - field to read from
     */
    this.readCustom = function($field){
        self.ref = $field.find('> .customRef').val();
        self.data = {};

        $field.find('> .field').each(function(){
            var data = new dataPoint();
            data.read( $(this) );
            if ( data.data )
                self.data[ data.ref ] = data.data;    
        })
    }
    
    /**
     * readMany
     * - read a field that can have multiple entries 
     * @param jQuery dom object $field - field to read from
     */
    this.readMany = function($field){
        self.ref = $field.find('> .manyRef').val();
        self.data = [];
        $field.find('> .manyBody > .collapsibleBody').each(function(){
            $(this).find('.field')
            var data = new dataPoint();
            data.readBlock( $(this) );
            if ( data.data )
                self.data[ self.data.length ] = data.data;    
        });
    }
    
    /**
     * readFile
     * - read a file field 
     * @param jQuery dom object $field - field to read from
     */
    this.readFile = function($field){
        var data = $field.data();
        self.ref = data.ref;
        self.data = $field.find('.fileID').val();
    }
    
    /**
     * readDate
     * - read a date field 
     * @param jQuery dom object $field - field to read from
     */
    this.readDate = function($field){
        var data = $field.data();
        self.ref = data.ref;
        self.data = $field.find('.fileID').val();
    }
    
    /**
     * readInput
     * - read a standard field 
     * @param jQuery dom object $input - input to read from
     */
    this.readInput = function($input){
        self.ref = $input.attr('id');
        self.data = $input.val();
        if ( self.data == 'no_selection' )
            self.data = null;
    }
}
