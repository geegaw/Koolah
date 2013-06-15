/**
 * @fileOverview defines FolderTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FolderTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\folders
 * @extends Node
 * @class - folder to store other folders, pages, or widgets
 * @constructor
 * @param string parentID
 * @param jQuery dom object $msgBlock
 */
function FolderTYPE( parentID, $msgBlock ) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node('KoolahFolder');
    
    /**
     * parentID - parent id
     *@type string
     */
    this.parentID = parentID;
    
    /**
     * label - folder label
     *@type LabelTYPE
     * @default New Folder
     */
    this.label = new LabelTYPE();
    this.label.label = 'New Folder';
    
    /**
     * children - children elements
     *@type array
     */
    this.children = [];
    
    /**
     * jsID - unique id for dom 
     *@type string
     */
    this.jsID = 'folder'+( new Date().getTime() ); 
    
    /**
     * $folder - dom reference
     *@type jQuery dom object
     */
    this.$folder = null;
    
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
    this.get = function(callback, $el, async) { self.parent.get(self.fromAJAX, callback, $el, async); }
    
    /**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function(callback, $el, aysnc) { 
        self.predelete();
        if (!$el)
            $el = self.$msgBlock;
        self.parent.del(self.removeEl, callback, $el, aysnc); 
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
    this.equals = function(folder) { return self.parent.equals(folder); }
    //*** /parent extensions ***//
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'FolderTYPE'; }
    
    /**
     * getRoot
     * - gets the root folder which trees out to all folders
     * @uses handleRoot
     * @param string callback - function name
     * @param jQuery dom object $el -optional - where the message will be displayed
     */
    this.getRoot = function(callback, $el){
        if (!$el)
            $el = self.$msgBlock;
        var q = ['ref='+FOLDER_COLLECTION_ROOT];
        new Nodes('KoolahFolder').get( self.handleRoot, callback,  q, $el );
    }
    
    /**
     * handleRoot
     * - turns get Root into a folder object
     * @param array response 
     */
    this.handleRoot = function( response ){
        if ( response && response.nodes ){
            var data = response.nodes;
            self.parent.fromAJAX( data );
            self.fromAJAX( data );
        }        
    }
    
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
     * @param json data
     */
    this.fromAJAX = function(data){
        self.parentID = data.parentID;
        self.label.fromAJAX( data );
        self.children = [];
        if ( data.children ){
            for (var i=0; i < data.children.length; i++){
                var child = data.children[i];
                var obj = new window[child.className]();
                obj.parent.id = child.id;
                obj.label.label = child.label;
                obj.$msgBlock = self.$msgBlock;
                obj.init();
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
        tmp.parentID = self.parentID;
        tmp.children = [];
        if ( self.children && self.children.length ){
            for( var i = 0; i < self.children.length; i++ ){
                var child = self.children[i];
                var tmpChild = {
                    'className': child.get_class(),
                    'id'       : child.parent.id
                }
                tmp.children[ tmp.children.length ]= tmpChild;
            }
        }
        return tmp;
    }
    
    /**
     * showInput
     * - shows the folders input fields 
     */
    this.showInput = function(){
        self.$folder.find('.name').hide();
        self.$folder.find('.changeFolderName').show().focus();
    }
    
    /**
     * hideInput
     * - hides the folders input fields 
     */
    this.hideInput = function(){
        self.$folder.find('.changeFolderName').hide();
        self.$folder.find('.name').show();
    }
    
    /**
     * mkFolder
     * - make folder html 
     */
    this.mkFolder = function(){
        var label = self.label.label
        if ( label == 'New Folder' )
            label = '';
        var html = '';
        html+=      '<div id="'+self.jsID+'" class="folder">';
        html+=          '<a href="#" class="folderClick">';
        html+=              '<span class="tabTop">&nbsp;</span>';
        html+=              '<span class="folderBody"></span>';
        html+=              '<span class="name fullWidth">'+self.label.label+'</span>';
        html+=          '</a>';
        html+=          self.mkOptions();
        html+=          '<input type="text" class="changeFolderName hide" placeholder="name" value="'+label+'"/>';
        html+=      '</div>';
        return html;
    }
    
    /**
     * mkOptions
     * - make folder options html 
     */
    this.mkOptions = function(){
        var html = '';
        html+=          '<div class="folderOptions hide">';
        html+=              '<div class="folderOption"><a href="#" class="rename">rename</a></div>';
        html+=              '<div class="folderOption"><a href="#" class="del delete">delete</a></div>';
        html+=          '</div>';    
        return html;
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
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
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
     * showChildren
     * - finds suspect in the list
     * @param jQuery dom object $el - where to show the children
     */
    this.showChildren = function( $where ){
        if ( self.children && self.children.length ){
            for ( var i=0; i < self.children.length; i++ ){
                var child = self.children[i];
                child.mkIcon($where);
            }
        }  
    }
    
    /**
     * getChildren
     * - get from ajax folders children
     */
    this.getChildren = function(){
        if ( self.children && self.children.length ){
            for ( var i=0; i < self.children.length; i++ ){
                var child = self.children[i];
                child.get( null, self.$msgBlock );
            }
        }
    }
    
    /**
     * mkIcon
     * - add folder to dom
     * @param jQuery dom object $el - where to show the children
     */
    this.mkIcon = function( $where ){
        self.init();
        $where.append( self.$folder );
        self.get( self.getChildren, self.$msgBlock );
    }
    
    /**
     * predelete
     * @TODO vefify
     * - before delete, delete all children
     */
    this.predelete = function(){
        if( self.children.length ){
            for(var i=0; i<self.children.length; i++ ){
                var child = self.children[i];
                obj = new window[ child.className ]();
                obj.parent.id = child.id;
                obj.del( null, self.$msgBlock  );
            }
        }
    }
    
    /**
     * removeEl
     * @TODO vefify
     * - remove self from dom
     */
    this.removeEl = function(){
        self.$folder.remove();    
    }
    
    /**
     * removeChild
     * - remove child from children
     * @param mixed child
     */
    this.removeChild = function( child ){
        var pos = self.findPos( child );
        child = self.children[pos];
        self.children.splice(pos, 1);
    }
    
    /**
     * init
     * - init a folder in the dom
     * and all of its clickable actions
     */
    this.init = function(){
        self.$folder = $( this.mkFolder() );

        self.$folder.find('.folderClick').click( function(){
            var $parent = $(this).parents('.tabBody'); 
            
            $('.folder').remove();
            $('.page').remove();
            
            self.showChildren( $parent );
        })
         
         self.$folder.bind("contextmenu", function (e){
            $(this).find('.folderOptions').show();
            $('body').click(function(){ $('.folderOptions').hide() })
            e.stopPropagation();  
            return false;
        })
        
        self.$folder.find('.rename').click(function(){
            self.$folder.find('.folderOptions').hide();
            self.showInput();
            return false;
        })
        
        self.$folder.find('.changeFolderName').blur(function(){
            var val = $.trim( $(this).val() )
            if ( !val.length )
                val = 'New Folder';
            self.label.label = val;
            self.$folder.find('.name').html( self.label.label );        
            self.hideInput();
            self.save(null, self.$msgBlock);
        })
        
        self.$folder.find('.del').click(function(){
            var $parent = $(this).parents('.folder');
            $parent.find('.folderOptions').hide();
            
            var msg = self.label.label;
            if ( self.children.length > 0 ){
                msg+= "<br />This folder contains "+self.children.length+" sub-folders/pages.";
                msg+= "<br />These will all be deleted as well";
            }
            new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#list'), msg);
            
            return false;
        })
        
    }
    self.init();
    
    $('#'+self.jsID+'deleteConfirm').live('click', function(){
        closeOverlay();
        self.del(null, self.$msgBlock);
        return false;
    })
}