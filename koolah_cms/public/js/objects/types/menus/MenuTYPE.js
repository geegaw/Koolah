/**
 * @fileOverview defines MenuTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MenuTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\menus
 * @extends Node
 * @class - data for a menu item
 * @constructor
 * @param string parentID
 * @param jQuery dom object $msgBlock
 */
function MenuTYPE(parentID, $msgBlock){
    
    /**
     * parent - extend Node
     * @type Node
     */
    this.parent = new Node('KoolahMenu');
    
    /**
     * parentID - parent menu's id
     * @type string
     */
    this.parentID = parentID;
    
    /**
     * label - folder label
     * @type LabelTYPE
     * @default 'New Menu'
     */
    this.label = new LabelTYPE();
    this.label.label = 'New Menu'
    
    /**
     * url - url for menu item to go to
     * @type string
     * @default ''
     */
    this.url = '';
    
    /**
     * newTab - should menu open in new tab
     * @type bool
     * @default false
     */
    this.newTab = false;
    
    /**
     * children - sub menu items
     * @type array
     */
    this.children = [];
    
    /**
     * jsID - unique id for dom 
     *@type string
     */
    this.jsID = 'menu'+UID(); 
    
    /**
     * $msgBlock - dom reference to where to display messages
     *@type jQuery dom object
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
    this.save = function(callback, $el) { self.parent.save(self.toAJAX(), null, callback, $el); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function(callback, $el, aysnc) { self.parent.get(self.fromAJAX, callback, $el, aysnc); }
    
    /**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function(callback, $el, async) {
        self.delChildren($el); 
        self.removeFromParent($el);
        self.parent.del(null,callback, $el, async); 
    }
    
    /**
     * getID:
     * - return id
     * @returns string id
     */
    this.getID = function() { return self.parent.getID(); }
    
    /**
     * equals
     * - compare two ids to determine
     * if object is same
     * @param string folder - suspect id
     * @returns bool
     */
    this.equals = function(page) { return self.parent.equals(page); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'MenuTYPE'; }
    //*** /parent extensions ***//
    
    /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.children=[]; }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( child ){ self.children[ self.children.length ] = child; }
    
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function(data){
        self.parentID = data.parentID;
        self.newTab = data.newTab;
        self.url = data.url;
        self.label.fromAJAX( data );
        
        self.children = [];
        if ( data.children ){
            for (var i=0; i < data.children.length; i++){
                var child = data.children[i];
                var obj = new MenuTYPE(null, self.$msgBlock);
                obj.parent.id = child.id;
                obj.label.label = child.label
                self.append( obj );
            }
        }
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = self.label.toAJAX();
        tmp.url = self.url;
        tmp.newTab = self.newTab;
        tmp.parentID = self.parentID;
        tmp.children = [];
        if ( self.children && self.children.length ){
            for( var i = 0; i < self.children.length; i++ ){
                var child = self.children[i];
                tmp.children[ tmp.children.length ]= child.parent.id;
            }
        }
        return tmp;
    }
    
    /**
     * compare
     * - compare two folders
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
     * mkInput
     * - make html for menus 
     * @returns string
     */
    this.mkInput = function(){
        var html = '';
        html+='<li id="'+self.jsID+'" class="fullWidth menuItem">';
        html+=  '<input type="hidden" class="menuID" value="'+self.parent.id+'" />';
        if (!self.parentID )
            html+=  '<a href="#" class="aMenu">'+self.label.label+'</a>';
        else
            html+=  '<span class="aMenu">'+self.label.label+'</span>';
        html+=      '<span class="commands">';
        html+=          '<a href="#" class="edit">edit</a>';
        html+=          '<a href="#" class="del deleteMenu">X</a>';
        html+=      '</span>';
        if (self.parentID){
            //if (self.children && self.children.length){
                html+= '<div class="collapsible fullWidth">';
                html+=      '<div class="submenu fullWidth">Submenu <a href="#" class="closed toggle"><span class="square">&nbsp;</span></a></div>';
            //}
            html+=      '<ul class="items collapsibleBody fullWidth hide">';
            html+=          self.mkChildren();
            html+=      '</ul>';
            //if (self.children && self.children.length)
                html+= '</div>';
        }
        html+='</li>';

        return html;
    }
    
    /**
     * mkChildren
     * - make children menus html
     * @returns string 
     */
    this.mkChildren = function(){
        var html = '';
        if ( self.children ){
            for( var i=0; i< self.children.length; i++ ){
                var child = self.children[i];
                child.get(null, null, false);
                html+= child.mkInput();
            }
        }
        return html;
    }
    
    /**
     * delChildren
     * - delete children and display message
     * @param jQuery dom object $el
     */
    this.delChildren = function( $el ){
        if ( self.chilren ){
            for( var i=0; i< self.children.length; i++ ){
                var child = self.children[i];
                child.del( null, $el );
            }
        }
    }
    
    /**
     * removeFromParent
     * - remove deleted menu from its parent
     * @param jQuery dom object $el
     */
    this.removeFromParent = function($el){
        if ( self.parentID ){
            var parentMenu = new MenuTYPE( null, self.$msgBlock );
            parentMenu.parent.id = self.parentID;
            parentMenu.get(null, self.$msgBlock, false);
            parentMenu.removeChild( self );
            parentMenu.save(null, self.$msgBlock );
        }
    }
    
    /**
     * removeChild
     * - remove child from children
     * @param string childID
     */
    this.removeChild = function( childID ){
        var pos = self.findPos( childID );
        self.children.splice(pos, 1);
    }

    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.children, suspect); }
    
    /**
     * findPos
     * - finds the position of suspect in the list
     * \n- returns -1 if not found
     * @param mixed suspect - suspect to look for
     * @returns int
     */
    this.findPos = function( suspect ){ return findPosInList(self.children, suspect); }
    
    
    /**
     * mkSortable
     * - make children menu items sortable
     */
    this.mkSortable = function(){
        $('.items').sortable({
            connectWith: '.items',
            stop: self.determineAction
        }).disableSelection();
    }
 
    /**
     * determineAction
     * - determine whether menu,
     * is being moved to its parent,
     * is being moved to a child,
     * is being moved to a new order
     * @param obj e - jQuery event object
     * @param obj ui - jQuery ui object
     */
    this.determineAction = function(e, ui){
        var $item = ui.item;
        var movingID = $item.find('.menuID').val();
        
        var movingMenu = new MenuTYPE( null, self.$msgBlock );
        movingMenu.parent.id = movingID;
        movingMenu.get(null, self.$msgBlock, false);
        
        var $parent = $item.parents('.list:first');
        
        var movingToID;
        if ( $parent.attr('id') == 'menusList' )
            movingToID = null;
        else{
            $parent= $item.parents('.items:first');
            console.log($parent)
            if ( $parent.parent().attr('id') == 'menuList' )
                movingToID = $('#menuID').val();
            else{
                movingToID = $parent.parents('.menuItem:first').find('> .menuID:first').val();
            }
        }  
        
        if (debug){
            console.log( movingMenu.label.label )
            console.log( movingMenu.parent.id )
            console.log( movingMenu.parentID )
            console.log( movingToID )
        }
       
        if ( movingMenu.parent.id === movingToID ){
            errorMsg( self.$msgBlock, 'You can not move an element inside of itself! This page will now reset... ' );
            //setTimeout( function(){ window.location = document.URL}, 1000 );
            //$('.items').remove();
            return;
        }
        else if( movingMenu.parentID === movingToID ){
            if ( movingMenu.parentID ){
                var parentMenu = new MenuTYPE( null, self.$msgBlock );
                parentMenu.parent.id = movingMenu.parentID;
                parentMenu.get(null, self.$msgBlock, false);
                parentMenu.updateChildrenOrder();
            }
        }
        else
            movingMenu.mv(movingToID);
        
    }
    
    /**
     * mv
     * - move menu to new destination
     * - display message
     * @pararm string toID - id of new menu item where being moved
     */
    this.mv = function( toID ){
        if (toID){
            var to = new MenuTYPE( null, self.$msgBlock )
            to.parent.id = toID;
            to.get(null, self.$msgBlock, false);
            to.append( self );
        }
        
        var previousParent = null
        if (self.parentID){
            previousParent = new MenuTYPE();
            previousParent.parent.id = self.parentID;
            previousParent.get(null, self.$msgBlock, false);
            previousParent.removeChild( self.parent.id );
        }
        
        self.parentID = toID;
        if (!toID)
            self.parentID = -1;
        
        self.save( null, self.$msgBlock );
        if (previousParent)
            previousParent.save( null, self.$msgBlock );
        if (toID)
            to.save( null, self.$msgBlock );
    }
    
    /**
     * updateChildrenOrder
     * - move menu to new destination
     * @pararm string toID - id of new menu item where being moved
     */
    this.updateChildrenOrder = function(){
        console.log(self.label.label)
        
        var $items = null;
        if ( $('#menuID').val() === self.parent.id )
            $items = $('#menuList > .items');                    
        else{
            var $parent = $('.menuID[value='+self.parent.id+']').parents('.menuItem:first');
            console.log($parent)
            $items = $parent.find('.items:first');
        }
        
        if ($items.length){
            self.clear();
            $items.find('> li').each(function(){
                var obj = new MenuTYPE(null, self.$msgBlock);
                obj.parent.id = $(this).find('.menuID').val();
                obj.label.label = $(this).find('.aMenu').html();
                self.append( obj );
             })
             
             self.save( null, self.$msgBlock );
        }
    }
    
    /* Actions */
    $('body').on('click', '#'+self.jsID+' a.aMenu', function(){    
        $('#menuID').val( self.parent.id );
         
        $('#menuList h1 span').html( self.label.label );
        $('#menuList .items').html( self.mkChildren() );
        $('#menuList').show();
        
        self.mkSortable(); 
        return false;
    })
    
     $('body').on('click', '#'+self.jsID+' .edit', function(){
        $('#menuName').val( self.label.label );
        if ( self.parentID ){
            $('#fullForm').show();
            $('#meuURL').val(self.url);
            if ( self.newTab )
                $('#menuNewtab').attr('checked', 'checked');
        }
        else
            $('#fullForm').hide();
        
        $('#newMenuForm').find('.save').attr('id', 'save'+self.parent.id  );
        $('#newMenuForm').show();
        
        return false;
    })
    
    /*
    $('body').on('click', '#save'+self.jsID, function(){
        var $form = $(this).parents('form');
        var form = new FormTYPE( $form );
        
        console.log('here')
        
        if ( form.validate() ){
            var parentID = null;

            self.label.label =  $.trim( $('#menuName').val() );
            self.url = $.trim( $('#meuURL').val() );
            self.newTab = $('#menuNewtab').attr('checked');

            if ( !self.parent.id && self.parentID ){
                self.save( null, $msgBlock );
                var parent = new MenuTYPE(null, self.$msgBlock);
                parent.parent.id = self.parentID;
                parent.get( null, self.$msgBlock, false );
                parent.append( self );
                parent.save(null, self.$msgBlock);
            }
            else
                self.save( null, self.$msgBlock );
            
            form.resetForm();
            form.$el.hide();
            new Overlay.close();                
        }
        return false;

    })
    */
    $('body').on('click', '#'+self.jsID+' .deleteMenu', function(){
    //$('#'+this.jsID+' .deleteMenu').live('click', function(){
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $(this).parents('.list'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm', function(){
    //$('#'+this.jsID+'deleteConfirm').live('click', function(){
        self.del( null, self.$msgBlock )
        closeOverlay();
        $('#'+self.jsID).remove();
        return false;
    })

}
