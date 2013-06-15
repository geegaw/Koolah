function PageTYPE($msgBlock) {
    this.parent = new Node('KoolahPage');

    this.label = new LabelTYPE();
    this.label.label = 'New Page'
    this.seo = new seoTYPE();
    this.publicationStatus = $('#publicationStatus .status').html();
    this.templateID = '';
    this.template = new TemplateTYPE($msgBlock);
    this.data = {};
    
    this.jsID = 'page'+UID(); 
    this.$msgBlock = $msgBlock;
    this.$icon;
    
    var self = this;

    /**
     * parent extensions
     */
    //this.save = function(callback, $el) {}
    this.save = function(callback, $el) { self.parent.save(self.toAJAX(), null, callback, $el); }
    this.get = function(callback, $el) { self.parent.get(self.fromAJAX, callback, $el); }
    this.del = function(callback, $el) { self.parent.del(null, callback, $el); }
    this.getID = function() { return self.parent.getID(); }
    this.equals = function(page) { return self.parent.equals(page); }
    /***/
    
    this.get_class = function(){ return 'PageTYPE'; }
    this.getTemplate = function(){ self.template.get(null, $msgBlock, false);}
    
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
     * methods
     */
    this.fromAJAX = function(data) {
        if (!self.parent.id)
            self.parent.fromAJAX(data);
        self.label.fromAJAX(data);
        self.seo.fromAJAX(data.seo);
        self.publicationStatus = data.publicationStatus;
        self.templateID = data.templateID;
        self.template.parent.id = self.templateID; 
        self.data = data.data;        
    }

    this.toAJAX = function() {
        var tmp = self.label.toAJAX();
        tmp.seo = self.seo.toAJAX();
        tmp.publicationStatus = self.publicationStatus;
        tmp.templateID = self.templateID;
        tmp.data = self.data;
        return tmp;
    }

    this.mkInput = function() {
        var html = '';
        return html;
    }
    
    this.showInput = function(){
        self.$icon.find('.name').hide();
        self.$icon.find('.changeName').show().focus();
    }
    
    this.hideInput = function(){
        self.$icon.find('.changeName').hide();
        self.$icon.find('.name').show();
    }
    
    
    this.mkPageIcon = function(){
        var label = self.label.label
        if ( label == 'New Page' )
            label = '';
        var publicationStatus = '';
        
        html = '';
        html+=      '<div id="'+self.jsID+'" class="page">';
        html+=          '<a href="page?id='+self.parent.id+'" class="pageClick">';
        html+=              '<span class="pageBody">';
        html+=                  '<span class="pageTemplate '+self.templateID+'"></span>';
        html+=                  '<span class="pagePublicationStatus '+self.publicationStatus+'">'+self.publicationStatus+'</span>';
        html+=              '</span>';
        html+=              '<span class="name fullWidth">'+self.label.label+'</span>';
        html+=          '</a>';
        html+=          self.mkOptions();
        html+=          '<input type="text" class="changeName hide" placeholder="name" value="'+label+'"/>';
        html+=      '</div>';
        return html;
    }
    
    this.mkIcon = function($where){
        self.get(null, self.$msgBlock, false);
        self.init();
console.log( self )        
        $where.append( self.$icon );
    }
    
    this.mkOptions = function(){
        var html = '';
        html+=          '<div class="pageOptions hide">';
        html+=              '<div class="pageOption"><a href="#" class="rename">rename</a></div>';
        html+=              '<div class="pageOption"><a href="#" class="del delete">delete</a></div>';
        html+=          '</div>';    
        return html;
    }
    
    this.mkList = function( params, tagParams ){
        self.getTemplate();
        var html = '';
        html+= '<li id="'+self.jsID+'" class="page" data-id="'+self.parent.id+'" data-label="'+self.label.label+'" >';
        html+=      '<span class="type">Page</span>';
        html+=      '<span class="name"><a href="page?id='+self.parent.id+'" class="pageClick">'+self.label.label+'</a></span>';
        html+=      '<span class="template"><a href="template?templateID='+self.templateID+'" class="pageClick">'+self.template.label.label+'</a></span>';
        html+=      '<span class="pageFolder">'+'&nbsp;'+'</span>';
        html+=      '<span class="status">'+self.publicationStatus+'</span>';
        html+=      '<span class="commands">';
        if ( params && params.editable ){
            html+=       '<button class="edit">edit</button>';
            html+=       '<button class="del">X</button>';
        }
        html+=      '</span>';
        html+= '</li>';
        return html;
    }
    
    this.readForm = function($form) {
        self.label.label = $('#newPageWidgetName').val();
        if ($('.workflowOptions').val() != 'no_selection')
            self.publicationStatus = $('.workflowOptions').val();
        self.seo.readForm( $form );
        self.readData( $form );
        console.log( $form )
        console.log( self )
    }
    
    this.readData = function($form){
        self.data = [];
        var data = new dataPoint();
        data.readBlock( $form.find('> .tabBody') );
        self.data = data.data;
    }

    this.fillForm = function() {
    }
    
    this.removeEl = function(){
console.log( 'remove' )        
console.log( self.$icon)
console.log( self.jsID)
        self.$icon.remove();    
        return false;
    }

    
    /***/
    
    /***/

    this.init = function(){
        self.$icon = $( this.mkPageIcon() );

        self.$icon.find('.folderClick').click( function(){
        })
         
         self.$icon.bind("contextmenu", function (e){
            $(this).find('.pageOptions').show();
            $('body').click(function(){ $('.pageOptions').hide() })
            e.stopPropagation();  
            return false;
        })
        
        self.$icon.find('.rename').click(function(){
            self.$icon.find('.pageOptions').hide();
            self.showInput();
            return false;
        })
        
        self.$icon.find('.changeName').blur(function(){
            var val = $.trim( $(this).val() )
            if ( !val.length )
                val = 'New Page';
            self.label.label = val;
            self.$icon.find('.name').html( self.label.label );        
            self.hideInput();
            self.save(null, self.$msgBlock);
        })
        
        self.$icon.find('.del').click(function(){
            var $parent = $(this).parents('.page');
            $parent.find('.pageOptions').hide();
            var msg = "Are you sure you want to delete "+self.label.label;
            new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#list'), msg);
        })
        
        
        
        
    }
    
    $('#'+self.jsID+'deleteConfirm').live('click', function(){
        closeOverlay()
        self.del(self.removeEl, self.$msgBlock);
        return false;
    })
    
}