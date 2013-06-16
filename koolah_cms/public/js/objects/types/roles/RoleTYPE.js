/**
 * @fileOverview defines RoleTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * RoleTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Node
 * @class - handles data for a user role
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function RoleTYPE($msgBlock){
	
	/**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahRole' );
	
	
	//TODO convert to labelTYPE
	/**
     * name - name 
     * @type string
     */
    this.name = '';
	
	/**
     * ref - reference 
     * @type string
     */
    this.ref = '';
	
	/**
     * permissions - permissions list 
     * @type array
     */
    this.permissions = [];
	
	/**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'role'+UID(); 
    
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
    this.save = function( callback, $el ){ self.parent.save( self.toAJAX(), null,  callback, $el ); }
	
	/**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function( callback, $el ){ self.parent.get( self.fromAJAX, callback, $el ); }	
	
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
    this.equals = function( role ){ return self.parent.equals( role ); }
	/**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'RoleTYPE'; }
    //*** /parent extensions ***//
    
	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
		self.name = data.label;
		self.ref = data.ref;
		self.permissions = data.permissions;
	}

	/**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
		var tmp = {}
			tmp.label = self.name;
			tmp.ref = self.ref;
			tmp.permissions = self.permissions;
		return tmp;
	}
	
	/**
     * mkInput
     * - make html for role 
     * @returns string
     */
    this.mkInput = function(){
		var html = '';
		html+= '<li class="role">';
		html+=		'<span class="roleName" date-id="'+self.getID()+'">'+self.name+'</span>';
		html+=  	'<input type="hidden" class="roleID" value="'+self.getID()+'" />';
		html+=		'<span class="commands">';
		html+= 		'<button class="edit">edit</button>';
		html+= 		'<button class="del">del</button>';
		html+=		'</span>'; 
		html+= '</li>';
		return html;
	}
	
	/**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form ){
		self.parent.id = $('#roleID').val();
		self.name = $('#roleName').val();
		
		self.permissions = [];
		$form.find('.permission:checked').each(function(){
            console.log($(this))
            self.permissions[ self.permissions.length ] = $(this).val();
		})
	}
	
	/**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
		$('#roleID').val( self.parent.id );
		$('#roleName').val( self.name );
		if ( self.permissions.length ){
			for ( var i=0; i < self.permissions.length; i++ )
				$('#'+self.permissions[i]  ).attr( 'checked', 'checked' );
		}
	}
}