/**
 * @fileOverview defines seoTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * seoTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Node
 * @class - handles all seo information for a page
 * @constructor
 */
function seoTYPE() {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node('PageTYPE');

    /**
     * title - seo page title 
     * @type string
     * @default ''
     */
    this.title = '';
    
    /**
     * description - seo page description 
     * @type string
     * @default ''
     */
    this.description = '';
    
    /**
     * aliases - page aliases 
     * @type AliasesTYPE
     */
    this.aliases = new AliasesTYPE( $('#seoModule .aliases:first') );
    
    var self = this;

    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function(data) {
        self.aliases.fromAJAX(data.aliases);
        self.title = data.title;
        self.description = data.description;
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function() {
        var tmp = {};
        tmp.aliases = self.aliases.toAJAX();
        tmp.title = self.title;
        tmp.description = self.description;
        return tmp;
    }

    /**
     * mkInput
     * - make html for menus 
     * @returns string
     */
    this.mkInput = function() {
        var html = '';
        return html;
    }

    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function($form) {
        self.title = $('#seoModuleTitleID').val();
        self.description = $('#seoModuleDescriptionID').val();
        self.aliases.readForm($form);
    }

    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        $('#seoModuleTitleID').val( self.title );
        $('#seoModuleDescriptionID').val( self.description );
        self.aliases.fillForm();
    }
}