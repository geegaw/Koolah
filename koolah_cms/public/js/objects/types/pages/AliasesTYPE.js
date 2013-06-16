/**
 * @fileOverview defines AliasesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * AliasesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Nodes
 * @class - works with multiple aliases
 * @constructor
 * @param jQuery dom object $el
 */
function AliasesTYPE($el) {
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes('KoolahAliases');
    
    /**
     * $el - dom reference to self
     *@type jQuery dom object
     */
    this.$el = $el;
    
    var self = this;
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'AliasesTYPE'; }
    
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
    this.append = function( alias ){ self.parent.append( alias ); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.get = function( callback, args, $el, async ){ self.parent.get( self.fromAJAX, callback, args, $el, async ); }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.aliases(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        //var results = new TemplatesTYPE();
        //results.parent.nodes = filterList( self.templates(), suspect, by ); 
        //return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.aliases().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
   /**
    * aliases
    * - easy call to get nodes
    * @returns array
    */
    this.aliases = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
   
    
    /**
     * appendToPage
     * - append new alias to page
     * @param AliasType alias
     */
    this.appendToPage = function( alias ){
        self.$el.find('.aliasesList').append( alias.mkPod() );
        self.mkSortable();
    }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function(data) {
        if (data && data.aliases && data.aliases.length){
            for(var i=0; i < data.aliases.length; i++ ){
                var alias =  new AliasTYPE();
                alias = alias.fromAJAX( data.aliases[i] )
                self.append( alias );
            }
        }
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function() {
        var tmp = [];
        if (self.count()){
            for(var i=0; i < self.count(); i++ ){
                var alias =  self.aliases()[i];
                tmp[tmp.length]= alias.toAJAX();
            }
            
        }
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
     * @param jQuery dom object $form - form to read from
     */
    this.readForm = function($form) {
        if ( $form && !self.$el )
            self.$el = $form.find('.aliases');
        
        self.clear();
        self.$el.find('.alias').each(function(){
            var alias = new AliasTYPE();
            alias.jsID = $(this).attr('id');
            alias.readForm();
            self.parent.append( alias );           
        })
    }

    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function() {
        if ( self.count() ){
            for( var i=0; i< self.count(); i++  ){
                var alias = self.aliases()[i];
                self.appendToPage( alias );
            }
        }
        self.mkSortable();
    }
    
    /**
     * mkSortable
     * - make children aliases sortable
     */
    this.mkSortable = function(){    
        self.$el.find('.aliasesList ').sortable({
            items: '.alias',
            update: self.readForm
        });
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.aliases(), suspect);
        if (pos>=0)
            self.aliases().splice(pos, 1);
        console.log( pos )
    }
}