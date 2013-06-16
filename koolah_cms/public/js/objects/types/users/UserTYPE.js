/**
 * @fileOverview defines UserTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\users
 * @extends Node
 * @class - handles data for a user
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function UserTYPE($msgBlock){
	
	/**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahUser' );
	
	/**
     * name - user's name -ex Firstname Lastname 
     * @type string
     */
    this.name = '';
	
	/**
     * username - username(email) 
     * @type string
     */
    this.username = '';
	
	/**
     * active - if user is still active
     * - NOTE: only super users can perm delete a user 
     * @type bool
     * @default true
     */
    this.active = true;
	
	/**
     * roles - user roles
     * @type array
     */
    this.roles = [];
	
	/**
     * roles - user permission in addition to their roles
     * @type array
     */
    this.permissions = [];
	
	/**
     * grantableRoles - roles user is allowed to grant
     * TODO decide if wanted
     * @type array
     */
    this.grantableRoles = [];
	
	/**
     * grantablePermissions - permissions user is allowed to grant
     * TODO decide if wanted
     * @type array
     */
    this.grantablePermissions = [];
	
	/**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'user'+UID(); 
    
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
    this.get = function( callback, $el ){ self.parent.get( self.fromAJAX, callback, $el ); }	
	
	/**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function( callback, $el ){ self.parent.del(null, callback, $el ); }
	
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
    this.equals = function( user ){ return self.parent.equals( user ); }
	
	/**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'UserTYPE'; }
    //*** /parent extensions ***//

	/**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
		self.name = data.name;
		self.username = data.username;
		self.active = data.active;
		self.roles = data.roles;
		self.permissions = data.permissions;
		self.grantableRoles = data.grantableRoles;
		self.grantablePermissions = data.grantablePermissions;
	}

	/**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
		var tmp = {}
			tmp.name = self.name;
			tmp.username = self.username;
			tmp.active = self.active;
			if ( self.password )
				tmp.password = self.password;
			tmp.roles = self.roles;
			tmp.permissions = self.permissions;
			tmp.grantableRoles = self.grantableRoles;
			tmp.grantablePermissions = self.grantablePermissions;
		return tmp;
	}
	
	/**
     * mkInput
     * - make html for page 
     * @returns string
     */
    this.mkInput = function(){
		var html = '';
		html+= '<li class="user">';
		html+=		'<span class="userName">'+self.name+'</span>';
		html+=  	'<input type="hidden" class="userID" value="'+self.getID()+'" />';
		html+=		'<span class="commands">';
		html+= 		'<button class="edit">edit</button>';
		html+= 		'<button class="del">del</button>';
		if ( !self.active )
			html+= 	'<button class="reactivate">reactivate</button>';
		html+=		'</span>'; 
		html+= '</li>';
		return html;
	}
	
	/**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form){
		self.parent.id = $('#userID').val();
		self.username = $('#userName').val();
		self.name = $('#name').val();
		self.password = $('#pass1').val();
		
		self.roles = [];
		self.permissions = [];
		
		if ( $('#superuser').attr('checked') )
			self.roles[0] = 'superuser';
		else if ( $('#admin').attr('checked') )
			self.roles[0] = 'admin';
		else{
			$form.find('.userRole.role:checked').each(function(){
				self.roles[ self.roles.length ] = $(this).val();
			})
			
			$form.find('.userPermission.permission:checked').each(function(){
				if ( !$(this).parent().hasClass('roleWrapper') )
					self.permissions[ self.permissions.length ] = $(this).val();
			})
			
			$form.find('.grantableRole.role:checked').each(function(){
				self.grantableRoles[ self.grantableRoles.length ] = $(this).val();
			})
			
			$form.find('.grantablePermission.permission:checked').each(function(){
				if ( !$(this).parent().hasClass('roleWrapper') )
					self.grantablePermissions[ self.grantablePermissions.length ] = $(this).val();
			})
		}
	}
	
	/**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
		$('#userID').val( self.getID() );
		$('#userName').val( self.username );
		$('#name').val( self.name );
		
		if ( self.roles && self.roles.length ){
			for ( var i=0; i < self.roles.length; i++ )
				$('#'+self.roles[i]  ).attr( 'checked', 'checked' );
		}
		
		if ( self.permissions && self.permissions.length ){
			for ( var i=0; i < self.permissions.length; i++ )
				$('#userPermission_'+self.permissions[i] ).attr( 'checked', 'checked' );
		}
		
		if ( self.grantableRoles && self.grantableRoles.length ){
			for ( var i=0; i < self.grantableRoles.length; i++ )
				$('#grantable_'+self.grantableRoles[i]  ).attr( 'checked', 'checked' );
		}
		
		if ( self.grantablePermissions && self.grantablePermissions.length ){
			for ( var i=0; i < self.grantablePermissions.length; i++ )
				$('#grantablePermission_'+self.grantablePermissions[i]  ).attr( 'checked', 'checked' );
		}
	}
	
	/**
     * reactivate
     * - reactivate a user
     * @param string callback - callback function  
     * @param jQuery dom obj $el - elemnet where to show message
     */
    this.reactivate = function( callback, $el ){ 
		self.active = true;
		self.parent.save( self.toAJAX(), null,  callback, $el );
	}
	
	/**
     * hasRole
     * - check if user has a desired role
     * @param string role   
     * @return bolol
     */
    this.hasRole = function( role ){ return listHas( self.roles, role ); }
	
	/**
     * hasPermission
     * - check if user has a desired permission
     * @param string permission   
     * @return bolol
     */
    this.hasPermission = function( permission ){ return listHas( self.permissions, permission ); }
	
	/**
     * isSuper
     * - check if user super user
     * @return bolol
     */
    this.isSuper = function(){ return self.hasRole( 'superuser' ); }
	
	/**
     * isAdmin
     * - check if user super admin
     * @return bolol
     */
    this.isAdmin = function(){ return (self.hasRole( 'superuser' ) || self.hasRole( 'admin' )); }
}