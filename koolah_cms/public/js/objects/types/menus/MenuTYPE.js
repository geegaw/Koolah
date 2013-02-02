function MenuTYPE(parentID, $msgBlock){
    this.parent = new Node('KoolahMenu');
    
    this.parentID = parentID;
    this.label = new LabelTYPE();
    this.label.label = 'New Menu'
    
    this.url = '';
    this.newTab = false;
    
    this.children = [];
    
    this.jsID = 'menu'+UID(); 
    
    this.$msgBlock = $msgBlock;
    
    var self = this;
    
    /**
     * parent extensions
     */
    //this.save = function(callback, $el) {}
    this.save = function(callback, $el) { self.parent.save(self.toAJAX(), null, callback, $el); }
    this.get = function(callback, $el, aysnc) { self.parent.get(self.fromAJAX, callback, $el, aysnc); }
    this.getID = function() { return self.parent.getID(); }
    this.equals = function(page) { return self.parent.equals(page); }
    /***/
    this.get_class = function(){ return 'MenuTYPE'; }
    this.clear = function(){ self.children=[]; }
    this.append = function( child ){ self.children[ self.children.length ] = child; }
    this.del = function(callback, $el) {
        self.delChildren($el); 
        self.removeFromParent($el);
        self.parent.del(null,callback, $el); 
    }
    
    
     /**
     * methods
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
    
    this.compare = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                return (suspect == self.parent.id) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
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
    
    this.delChildren = function( $el ){
        if ( self.chilren ){
            for( var i=0; i< self.children.length; i++ ){
                var child = self.children[i];
                child.del( null, $el );
            }
        }
    }
    
    this.removeFromParent = function($el){
        if ( self.parentID ){
            var parentMenu = new MenuTYPE( null, self.$msgBlock );
            parentMenu.parent.id = self.parentID;
            parentMenu.get(null, self.$msgBlock, false);
            parentMenu.removeChild( self );
            parentMenu.save(null, self.$msgBlock );
        }
    }
    
    this.removeChild = function( child ){
        var pos = self.findPos( child );
        self.children.splice(pos, 1);
    }

    this.find = function( suspect ){ return findInList(self.children, suspect); }
    this.findPos = function( suspect ){ return findPosInList(self.children, suspect); }
    
    
    this.mkSortable = function(){
        $('.items').sortable({
            connectWith: '.items',
            stop: self.determineAction
        }).disableSelection();
    }
 
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
    
    this.mv = function( toID ){
console.log('mv')        
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
        
        if (debug){
            console.log( toID )
            console.log(to.label.label)
            console.log(self)
            console.log(previousParent.label.label)
            console.log(previousParent.children.length)
        }
        self.save( null, self.$msgBlock );
        if (previousParent)
            previousParent.save( null, self.$msgBlock );
        if (toID)
            to.save( null, self.$msgBlock );
                            
    }
    
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
    
    debug = false;
    
    
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
