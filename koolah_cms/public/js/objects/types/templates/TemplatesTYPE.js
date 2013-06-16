/**
 * @fileOverview defines TemplatesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TemplatesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\templates
 * @extends Nodes
 * @class - works with multiple templates
 * @constructor
 */
function TemplatesTYPE(){
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahTemplates' );
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'TemplatesTYPE'; }
    
    //*** parent extensions ***//
    /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.parent.clear(); }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( template ){ self.parent.append( template ); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool aync
     */
    this.get = function( callback, args, $el, async ){ self.parent.get( self.fromAJAX, callback, args, $el, async ); }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.templates(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new TemplatesTYPE();
        results.parent.nodes = filterList( self.templates(), suspect, by ); 
        return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.templates().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * templates
     * - easy call to get nodes
     * @returns array
     */
    this.templates = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( response ){
        if ( self.parent.nodes && self.parent.nodes.length ){
            var tmp = self.parent.nodes.slice(0);
            self.clear(); 
            for (var i=0; i < tmp.length; i++){
                var node = tmp[i];
                var template = new TemplateTYPE();
                template.parent.fromAJAX( node );
                template.fromAJAX( node );
                self.append(template);
            }
        }
    }
    
    /**
     * sort
     * - sort by template tyep ex. pages, widgets, fields
     */
    this.sort = function(){
        for (var i=0; i < self.templates().length; i++){
            var template = self.templates()[i];
            if (template.templateType){           
                if(!self[ template.templateType ])
                    self[ template.templateType ] = [];
                self[ template.templateType ][ self[ template.templateType ].length ] = template;
             }
        }    
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.templates(), suspect);
        if (pos>=0)
            self.templates().splice(pos, 1);
           
    }
}