/**
 * @fileOverview defines TemplateTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TemplateTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\templates
 * @extends Node
 * @class - handles data for a template
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function TemplateTYPE($msgBlock){
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahTemplate' );
    
    /**
     * label - page label
     * @type LabelTYPE
     * @default 'New Page'
     */
    this.label = new LabelTYPE();
    
    /**
     * sections - sections inside of template
     * @type TemplateSectionsTYPE
     */
    this.sections = new TemplateSectionsTYPE();    
    
    /**
     * templateType - type of template 
     * @type string
     * @default ''
     */
    this.templateType = '';
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'template'+UID(); 
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    var self = this;
    
    //*** parent extensions ***//
    /**
     * save
     * - calls ajax to save and displays the status
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.save = function( callback, $el ){ self.parent.save( self.toAJAX(), null,  callback, $el );}
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function( callback, $el, async ){ self.parent.get( self.fromAJAX, callback, $el, async ); }    
    
    /**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function( callback, $el, async ){ self.parent.del(null, callback, $el, async ); }
    
    /**
     * getID:
     * - return id
     * @returns string id
     */
    this.getID = function(){ return self.parent.getID(); }
    
    /**
     * equals
     * - compare two ids to determine
     * if object is same
     * @param string folder - suspect id
     * @returns bool
     */
    this.equals = function( template ){ return self.parent.equals( template ); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'TemplateTYPE'; }
    //*** /parent extensions ***//

    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.label.fromAJAX( data );
        self.sections.fromAJAX( data.sections );
        self.templateType = data.templateType;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
            tmp.sections = self.sections.toAJAX();
            tmp.templateType = self.templateType;
        return tmp;
    }
    
    /**
     * mkList
     * - make html list view of page
     * @returns string
     */
    this.mkList = function(){
        var html = '';
        html+= '<li id="'+self.parent.id+'" class="template fullWidth">';
        html+=      '<span class="templateName name">'+self.label.label+'</span>';
        html+=      '<span class="commands">';
        html+=          '<a class="edit" href="template/?templateType='+self.templateType+'&templateID='+self.getID()+'" >edit</a>';
        html+=          '<a class="download" href="download/?classname='+self.parent.childClass+'&id='+self.getID()+'" >&#8595;</a>';
        html+=          '<a class="del" href="'+self.getID()+'" >X</a>';
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
        self.parent.id = $('#templateID').val();
        self.templateType = $('#templateType').val();
        
        self.label.label = $.trim( $('#templateName').val() );
        self.label.ref = $.trim( $('#templateNameRef').val() );
        
        if( self.templateType == 'field' ){
            var general = new TemplateSectionTYPE();
            general.name = 'general';
            self.sections.append( general );
        }
        else
            self.sections.readForm( $form );
        return self;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        $('#templateName').val( self.label.label );    
        self.sections.fillForm();
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
                return (suspect == self.parent.id) ? 'equals' : false;
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
       
    /**
     * getTypes
     * - get all template types
     * @returns array
     * NOTE: if adding types also must add in TemplateTYPE.php 
     */
    this.getTypes = function(){
        var types = [
                    'page', 
                    'widget',
                    'field'
                ];
        return types;
    }    
    
    /**
     * getAllFields
     * - get all fields in template
     * @returns array
     */
    this.getAllFields = function(){
        var fields = [];
        var sections = self.sections.sections();
        for (var i = 0; i < sections.length; i++){
            var section = sections[i];
            var sectionFields = section.fields.fields;
            fields = fields.concat( sectionFields );
        }
        return fields;
    }
}