function FolderTYPE( parentID, $msgBlock ) {
    this.parent = new Node('KoolahFolder');
    
    this.parentID = parentID;
    this.label = new LabelTYPE();
    this.label.label = 'New Folder';
    this.children = [];
    
    this.jsID = 'folder'+( new Date().getTime() ); 
    this.$folder = null;
    this.$msgBlock = $msgBlock;
    var self = this;

    this.get_class = function(){ return 'FolderTYPE'; }
    /**
     * parent extensions
     */
    this.save = function(callback, $el) { self.parent.save(self.toAJAX(), null, callback, $el); }
    this.get = function(callback, $el, async) { self.parent.get(self.fromAJAX, callback, $el, async); }
    this.del = function(callback, $el) { 
        self.predelete();
        self.parent.del(self.removeEl, callback, self.$msgBlock); 
    }
    this.getID = function() { return self.parent.getID(); }
    this.equals = function(folder) { return self.parent.equals(folder); }
    /***/
    
    this.getRoot = function(callback, $el){
        var q = ['ref='+FOLDER_COLLECTION_ROOT];
        new Nodes('KoolahFolder').get( self.handleRoot, callback,  q, $el );
    }
    
    this.handleRoot = function( response ){
        if ( response && response.nodes ){
            var data = response.nodes;
            self.parent.fromAJAX( data );
            self.fromAJAX( data );
        }        
    }
    
    this.clear = function(){ self.children=[]; }
    this.append = function( child ){ self.children[ self.children.length ] = child; }

       
    /**
     * methods
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
    
    this.showInput = function(){
        self.$folder.find('.name').hide();
        self.$folder.find('.changeFolderName').show().focus();
    }
    
    this.hideInput = function(){
        self.$folder.find('.changeFolderName').hide();
        self.$folder.find('.name').show();
    }
    
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
    
    this.mkOptions = function(){
        var html = '';
        html+=          '<div class="folderOptions hide">';
        html+=              '<div class="folderOption"><a href="#" class="rename">rename</a></div>';
        html+=              '<div class="folderOption"><a href="#" class="del delete">delete</a></div>';
        html+=          '</div>';    
        return html;
    }

    this.readForm = function(){
    }

    this.fillForm = function($body){
    }
    
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.jsID) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    this.find = function( suspect ){ return findInList(self.children, suspect); }
    this.findPos = function( suspect ){ return findPosInList(self.children, suspect); }
    
    this.showChildren = function( $where ){
        if ( self.children && self.children.length ){
            for ( var i=0; i < self.children.length; i++ ){
                var child = self.children[i];
                child.mkIcon($where);
            }
        }  
    }
    
    this.getChildren = function(){
        if ( self.children && self.children.length ){
            for ( var i=0; i < self.children.length; i++ ){
                var child = self.children[i];
                child.get( null, self.$msgBlock );
            }
        }
    }
    
    this.mkIcon = function( $where ){
        self.init();
        $where.append( self.$folder );
        self.get( self.getChildren, self.$msgBlock );
    }
    
    this.predelete = function(){
console.log( self.children.length )
        if( self.children.length ){
            for(var i=0; i<self.children.length; i++ ){
                var child = self.children[i];
                obj = new window[ child.className ]();
                obj.parent.id = child.id;
                obj.del( null, self.$msgBlock  );
            }
        }
    }
    
    this.removeEl = function(){
console.log( 'remove' )        
console.log( self.$folder)
console.log( self.jsID)
        self.$folder.remove();    
        return false;
    }
    
    this.removeChild = function( child ){
        var pos = self.findPos( child );
        child = self.children[pos];
        self.children.splice(pos, 1);
    }
    
    /***/

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