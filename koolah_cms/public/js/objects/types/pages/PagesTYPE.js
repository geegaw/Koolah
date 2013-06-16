/**
 * @fileOverview defines PagesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PagesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Nodes
 * @class - works with multiple pages
 * @constructor
 * @param jQuery dom object $el
 */
function PagesTYPE($el) {
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes('KoolahPages');

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
    this.get_class = function(){ return 'PagesTYPE'; }
    
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
    this.append = function( page ){ self.parent.append( page ); }
    
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
    this.find = function( suspect ){ return findInList(self.pages(), suspect); }
    
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
    this.count = function(){ return self.pages().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * pages
     * - easy call to get nodes
     * @returns array
     */
    this.pages = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function(data) {
        self.clear();
        if (data && data.nodes && data.nodes.length){
            for(var i=0; i < data.nodes.length; i++ ){
                var page =  new PageTYPE();
                page.fromAJAX( data.nodes[i] )
                self.append( page );
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
                var page =  self.pages()[i];
                tmp[tmp.length]= page.toAJAX();
            }
            
        }
        return tmp;
    }

    /**
     * readForm
     * - read data from form and fill in data 
     * @param jQuery dom object $form - form to read from
     */
    this.readForm = function($form) {
        if ( $form && !self.$el )
            self.$el = $form.find('.pages');
        
        self.clear();
        self.$el.find('.page').each(function(){
            var page = new PageTYPE();
            page.jsID = $(this).attr('id');
            page.readForm();
            self.parent.append( page );           
        })
    }

    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function() {
        if ( self.count() ){
            for( var i=0; i< self.count(); i++  ){
                var page = self.pages()[i];
                self.appendToPage( page );
            }
        }
        self.mkSortable();
    }
    
    /**
     * mkSortable
     * - make pages sortable
     */
    this.mkSortable = function(){    
        self.$el.find('.pagesList ').sortable({
            items: '.page',
            update: self.readForm
        });
    }
    
    /**
     * mkList
     * - make html list of pages
     * @param object params - pod options
     * @param object tagParams - pod options for tags
     * @returns string
     */
    this.mkList = function( params, tagParams ){
        var html = '';
        if ( self.pages() ){
            for (var i=0; i < self.pages().length; i++){
                var page = self.pages()[i];
                html += page.mkList( params, tagParams );   
            }
        }
        return html;
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.pages(), suspect);
        if (pos>=0)
            self.pages().splice(pos, 1);
    }
}