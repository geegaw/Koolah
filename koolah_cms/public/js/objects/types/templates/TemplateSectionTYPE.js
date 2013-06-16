/** * @fileOverview defines TemplateSectionTYPE * @license http://opensource.org/licenses/GPL-3.0 * @copyright Copyright (c) 2013 Christophe Vaugeois *//** * TemplateSectionTYPE *  * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a>  * @package koolah\cms\public\js\objects\types\templates * @class - data for a template section(tab)  * @constructor */function TemplateSectionTYPE(i){
    /**     * name - section name      * @type string     * @default ''     */    this.name = '';
        /**     * fields - fields in section      * @type FieldsTYPE     * @default ''     */    this.fields = new FieldsTYPE();
    
    /**     * jsID - unique id for dom      * @type string     */    this.jsID = 'section'+( new Date().getTime() )+i;
        var self = this;
    
    /**     * fromAJAX     * - convert ajax json response into proper Node     * @param array data     */    this.fromAJAX = function( data ){
        if ( data ){
            self.name = data.name;
            self.fields.fromAJAX( data.fields );
        }        
    }

    /**     * toAJAX     * - convert to assoc array object for      * easy json encoding for ajax     * @returns object     */    this.toAJAX = function(){
        var tmp = {}
            tmp.name = self.name;
            tmp.fields = self.fields.toAJAX();
        return tmp;
    }
    
    /**     * mkInput     * - make html for content in tab      * @returns string     */    this.mkInput = function(){
        var html = '';
        html += '<div id="'+self.jsID+'" class="section fullWidth hide">';
        html +=     '<div class="sectionBody fullWidth">';
        html +=         '<div class="fields fullWidth"></div>';
        html +=         '<div class="newField fullWidth hide"></div>';
        html +=         '<button type="button" class="addField center">Add New Field</button>';
        html +=         '<a href="#" class="delSection">delete section</a>';
        html +=     '</div>';
        html += '</div>';
        return html;
    }
    
    /**     * mkTab     * - make html for tab header      * @returns string     */    this.mkTab = function(){
        var html = '<div class="tab"><a href="'+self.jsID+'">'+self.name+'</a></div>';
        return html;
    }
    
    /**     * readForm     * - read data from form and fill in data     * @param jQuery dom obj $form - form to read from      */    this.readForm = function( $form ){
        self.name = $form.find('a').html();
        self.fields.readForm( $form );
        return self;
    }
    
    /**     * fillForm     * - fill in a form      */    this.fillForm = function(){        $('#addSection').before( self.mkTab() );        $('#sections').append( self.mkInput() );        self.fields.fillForm( $('#'+self.jsID) );
    }
    
    /**     * compare     * - compare two pages     * - can expand this function to accept more     * types, and/or return more then equals      * @param mixed suspect     * @returns mixed|bool     */    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
}