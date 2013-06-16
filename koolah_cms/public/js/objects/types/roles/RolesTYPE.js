/**
 * @fileOverview defines RolesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RolesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\roles
 * @extends Nodes
 * @class - works with user roles
 * @constructor
 */
function RolesTYPE(){
	
	/**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahRoles' );
	
	var self = this;

	/**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'RolesTYPE'; }
    
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
    this.append = function( user ){ self.parent.append( user ); }
	
	/**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool aync
     */
    this.get = function( callback, args, $el ){ self.parent.get( self.fromAJAX, callback, args, $el ); }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( user ){  return self.parent.find( user ); }
	
	/**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.roles().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * roles
     * - easy call to get nodes
     * @returns array
     */
    this.roles = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
	
	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( response ){
		self.parent.nodes = self.parent.nodes.roles;
		if ( self.parent.nodes && self.parent.nodes.length ){
			var tmp = self.parent.nodes.slice(0);
			self.clear(); 
			for (var i=0; i < tmp.length; i++){
				var node = tmp[i];
				var role = new RoleTYPE();
				role.parent.fromAJAX( node );
				role.fromAJAX( node );
				self.append(role);
			}
		}
	}
}