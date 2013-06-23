/**
 * @fileOverview defines PageTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PageTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Node
 * @class - handles data for a page
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function PageTYPE($msgBlock) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node('KoolahPage');

    /**
     * label - page label
     * @type LabelTYPE
     * @default 'New Page'
     */
    this.label = new LabelTYPE();
    this.label.label = 'New Page'
    
    /**
     * seo - seo info
     * @type seoTYPE
     * @default ''
     */
    this.seo = new seoTYPE();
    
    /**
     * publicationStatus - publication status 
     * @type string
     */
    this.publicationStatus = $('#publicationStatus .status').html();
    
    /**
     * templateID - parent template id 
     * @type string
     * @default ''
     */
    this.templateID = '';
    
    /**
     * template - parent template 
     * @type TemplateTYPE
     */
    this.template = new TemplateTYPE($msgBlock);
    
    /**
     * data - data on page 
     * @type object
     */
    this.data = {};
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'page'+UID(); 
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    /**
     * $icon - dom reference to display in folder view
     * @type jQuery dom object
     */
    this.$icon;
    
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
    this.del = function(callback, $el, async) { self.parent.del(null, callback, $el, async); }
    
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
    this.get_class = function(){ return 'PageTYPE'; }
    //*** /parent extensions ***//
    
    /**
     * getTemplate
     * - fetch the parent template via ajax
     * @returns string
     */
    this.getTemplate = function(){
        if (!self.template.getID())
             self.template.parent.id = self.templateID;
        self.template.get(null, $msgBlock, false);
    }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
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

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function() {
        var tmp = self.label.toAJAX();
        tmp.seo = self.seo.toAJAX();
        tmp.publicationStatus = self.publicationStatus;
        tmp.templateID = self.templateID;
        tmp.data = self.data;
        return tmp;
    }

    /**
     * showInput
     * - show input to create new page on folder view 
     */
    this.showInput = function(){
        self.$icon.find('.name').hide();
        self.$icon.find('.changeName').show().focus();
    }
    
    /**
     * hideInput
     * - hide input to create new page on folder view 
     */
    this.hideInput = function(){
        self.$icon.find('.changeName').hide();
        self.$icon.find('.name').show();
    }
    
    /**
     * mkPageIcon
     * - make html for page on folder view
     * @returns string 
     */
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
    
    /**
     * mkIcon
     * - create page icon and handle all
     * initiation on folder view
     * @param jQuery dom object $where - where to place icon
     */
    this.mkIcon = function($where){
        self.get(null, self.$msgBlock, false);
        self.init();
        $where.append( self.$icon );
    }
    
    /**
     * mkOptions
     * - make options for right click on folder view
     * @returns string
     */
    this.mkOptions = function(){
        var html = '';
        html+=          '<div class="pageOptions hide">';
        html+=              '<div class="pageOption"><a href="#" class="rename">rename</a></div>';
        html+=              '<div class="pageOption"><a href="#" class="del delete">delete</a></div>';
        html+=          '</div>';    
        return html;
    }
    
    /**
     * mkList
     * - make html list view of page
     * @param object params - pod options
     * @param object tagParams - pod options for tags
     * @returns string
     */
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
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function($form) {
        self.label.label = $('#newPageWidgetName').val();
        if ($('.workflowOptions').val() != 'no_selection')
            self.publicationStatus = $('.workflowOptions').val();
        self.seo.readForm( $form );
        self.readData( $form );
    }
    
    /**
     * readData
     * - read data from form
     * @param jQuery dom obj $form - form to read from 
     */
    this.readData = function($form){
        self.data = [];
        var data = new dataPoint();
        data.readBlock( $form.find('> .tabBody') );
        self.data = data.data;
    }

    /**
     * removeEl
     * - remove icon from folder view
     * @TODO verify 
     */
    this.removeEl = function(){
        self.$icon.remove();    
        return false;
    }
    
    /**
     * compare
     * - compare two pages
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
     * init
     * - init a page in the folder view
     * and all of its clickable actions
     */
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