/**
 * @fileOverview defines FieldsTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FieldsTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\templates
 * @class - handles data for fields
 * @constructor
 */
function FieldsTYPE() {
    
    /**
     * fields - array of fields
     * @type array
     */
    this.fields = [];
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'function FieldsTYPE'; }

    /**
     * clear
     * - empties nodes
     */
    this.clear = function() { self.fields = []; }

    /**
     * append
     * - appends a field
     * @param mixed field - field to append
     */
    this.append = function(field) {
        if ( field instanceof FieldTYPE )
            self.fields[self.fields.length] = field;
        else {
            var tmp = new FieldTYPE();
            tmp.fromAJAX(field);
            self.fields[self.fields.length] = tmp;
        }
    }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.fields, suspect); }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.fields.length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array fields
     */
    this.fromAJAX = function(fields) {
        self.clear();
        if (fields && fields.length) {
            for (var i = 0; i < fields.length; i++) {
                var field = new FieldTYPE();
                field.fromAJAX( fields[i] );
                self.append(field);
            }
        }
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = [];
        if (self.fields && !self.isEmpty() ){
            for( var i=0; i< self.count(); i++ )
                tmp[i]=self.fields[i].toAJAX();
        }    
        return tmp;    
    }

    /**
     * readForm
     * - read data from form and fill in data 
     * @param jQuery dom object $form - form to read from
     */
    this.readForm = function( $form ){
        self.clear();
        $form.find('.field').each(function(){
            var field = new FieldTYPE();
            self.append( field.readForm($(this)) );        
        });
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function( $section ){
        if ( self.fields && self.fields.length ){
            for( var i=0; i <  self.fields.length; i++)
                $section.find('.fields').append( self.fields[i].mkCapsule() )
        }
    }
    
    /**
     * update
     * - fill in a form 
     * @param jQuery dom object $form - form to update
     */
    this.update = function( $form ){
        var fields = [];
        $form.find('.field').each(function(){
            var $this = $(this);
            var field = self.find( $this.attr('id') );
            fields[fields.length] = field;
        });
        self.fields = fields;
        return self;
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.fields, suspect);
        if (pos>=0)
            self.fields.splice(pos, 1);
    }
}