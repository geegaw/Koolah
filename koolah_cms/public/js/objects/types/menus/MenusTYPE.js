/**
 * @fileOverview defines MenusTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MenusTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\menus
 * @extends Nodes
 * @class - works with multiple menus
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function MenusTYPE($msgBlock){
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahMenus' );
    
    /**
     * $msgBlock - dom reference to where to display messages
     *@type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'MenusTYPE'; }
    
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
    this.append = function( Menu ){ self.parent.append( Menu ); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.get = function(callback, $el){
        var q = ["parentID=null"];
        self.parent.get( self.fromAJAX, callback, q, $el );
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.menus().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.menus(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new MenusTYPE();
        results.parent.nodes = filterList( self.menus(), suspect, by ); 
        return results;
    }

   /**
    * menus
    * - easy call to get nodes
    * @returns array
    */
    this.menus = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
    
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( data ){
        self.parent.nodes = self.parent.nodes.Menus;
        if ( self.parent.nodes && self.parent.nodes.length ){
            var tmp = self.parent.nodes.slice(0);
            self.clear(); 
            for (var i=0; i < tmp.length; i++){
                var Menu = new MenuTYPE(null, self.$msgBlock);
                Menu.parent.fromAJAX( tmp[i] );
                Menu.fromAJAX( tmp[i] );
                self.append(Menu);
            }
        }
    }
    
    /**
     * mkInput
     * - make html for menus 
     * @returns string
     */
    this.mkInput = function(){
        var html = '';
        if ( self.menus() ){
            for( var i=0; i < self.menus().length; i++ ){
                menu = self.menus()[i];
                html += menu.mkInput();
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
        var pos = findPosInList(self.menus(), suspect);
        if (pos>=0)
            self.menus().splice(pos, 1);
    }
}