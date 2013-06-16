/**
 * @fileOverview defines FieldTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FieldTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\templates
 * @class - handles information about a field type 
 * @constructor
 */
function FieldTYPE(){
   
    /**
     * required - field is required
     * @type bool
     * @default false
     */
    this.required = false;
    
    /**
     * many - field can have multiple values
     * @type bool
     * @default false
     */
    this.many = false;
    
    /**
     * label - page label
     * @type LabelTYPE
     */
    this.label = new LabelTYPE();
    
    /**
     * options - field internal options 
     * @type string
     * @default ''
     */
    this.options = '';
    
    /**
     * type - field type 
     * @type string
     * @default ''
     */
    this.type = '';
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'field'+UID(); 
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'FieldTYPE'; }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        if (data){
            self.required = data.required;
            self.many = data.many;
            self.options = data.options;
            self.label.fromAJAX( data );
            //self.type.fromAJAX( data.type );
            self.type = data.type;
        }
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.required = self.required;
            tmp.many = self.many;
            tmp.options = self.options;
            //tmp.type = self.type.toAJAX();
            tmp.type = self.type;
        return tmp;
    }
    
    /**
     * mkCapsule
     * - make html capsule 
     * @returns string
     */
    this.mkCapsule = function(){
        var html = ''+ 
        '<div id="'+ self.jsID +'" class="field fullWidth">'+
        '   <div class="fieldInfo">'+
        '       <div class="fieldNameInfo">'+
        '           <div class="fieldName fullWidth">'+self.label.label+'</div>'+
        '           <div class="fieldSub fullWidth">';
        if ( self.required )
            html+= '    <div class="isRequired">Required</div>';
        if ( self.many )
            html+= '    <div class="many">can be many</div>';
        html += ''+
        '           </div>'+
        '       </div>'+
        '       <div class="fieldType">'+self.type+'</div>'+
        '       <div class="hide fieldOptions">'+self.options+'</div>'+
        '   </div>'+
        '   <div class="fieldInfoCommands">'+
        '       <button class="editField">edit</button>'+
        '       <button class="delField">X</button>'+
        '   </div>'+
        '</div>';
        return html;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form ){
        self.label.label = $.trim( $('#newFieldName').val() );
        self.label.ref = $.trim( $('#newFieldNameRef').val() );
        self.type = $('#fieldType').val();
        switch(self.type){
            case 'custom':
                self.options = $('#template').val();
                break;
            case'dropdown':
                self.options = $('#dropdownOptions').val();
                break;
            case 'file':
                self.options = $('#fileTypeSelect').val();
                break;
            case 'query':
                var query = new QueryTYPE();
                query.readForm();
                self.options = query.toAJAX();  
                break;
            default:
                break;
        }
        
        self.required = $('#isRequired').is(':checked');
        self.many = $('#many').is(':checked');
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        $('#newFieldName').val( self.label.label );
        $('#newFieldNameRef').val( self.label.ref );
        $('#fieldType option[value="'+self.type+'"]').attr('selected', 'selected');
        
        switch( self.type ){
            case 'custom':
                $('#template option[value="'+self.options+'"]').attr('selected', 'selected');
                $('#custom').show();
                break
            case 'dropdown':
                $('#dropdownOptions').val(self.options);
                $('#dropdown').show();
                break;
            case 'file':
                $('#fileTypeSelect option[value="'+self.options+'"]').attr('selected', 'selected');
                $('#fileType').show();
                break
            case 'query':
                var query = new QueryTYPE();
                query.fromAJAX( self.options );
                query.fillForm();  
                $('#queryType').show();
                break;
            default:
                break;
        }
        
        if ( self.required )
            $('#isRequired').attr('checked', 'checked');
        
        if ( self.many )
            $('#many').attr('checked', 'checked');
    }
    
    /**
     * compare
     * - compare two pages
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
    }
}