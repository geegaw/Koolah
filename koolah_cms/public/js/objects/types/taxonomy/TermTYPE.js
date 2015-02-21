/**
 * @fileOverview defines TermTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TermTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\pages
 * @extends Node
 * @class - handles data for a photo term
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function TermTYPE( $msgBlock ) {
    
    /**
     * parent - extend Node
     *@type Node
     */
    this.parent = new Node( 'KoolahTerm' );
    
    /**
     * term - term
     * @type string
     * @default ''
     */
    this.term = '';
    
    /**
     * parentID - parentID
     * @type string
     * @default ''
     */
    this.parentID = '';    
    
    /**
     * subterms - subterms 
     * @type TermsTYPE
     */
    this.subterms = new TermsTYPE($msgBlock);
    
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    /**
     * jsID - unique id for dom 
     * @type string
     */
    this.jsID = 'term'+UID();
    
    var self = this;
    
    //*** parent extensions ***//
    /**
     * save
     * - calls ajax to save and displays the status
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     */
    this.save = function( callback, $el ){ 
        if (!$el)
            $el = self.$msgBlock;
        self.parent.save( self.toAJAX(), null,  callback, $el );        
    }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool async - determine whether to run asynchronously 
     */
    this.get = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, $el, aysnc );
    }
    
    /**
     * getSubterms
     * - gets the terms subterms
     */
    this.getSubTerms = function(){ 
    	if (self.parent.id)
        	self.subterms.get(null,{parentID: self.parent.id}, self.$msgBlock, false); 
    }    
    
    /**
     * del
     * - deletes a node by id and classname stored internally
     * display status, remove self from dom
     * @param string callback - function name
     * @param jQuery dom object $el - optional - where the message will be displayed
     * @param bool async - determine whether to run asynchronously
     */
    this.del = function( callback, $el, aysnc ){ 
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.del(null, callback, $el, aysnc ); 
    }
    
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
    this.equals = function( term ){ return self.parent.equals( term ); }
    
    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'TermTYPE'; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array data
     */
    this.fromAJAX = function( data ){
        self.parent.fromAJAX( data );
        if ( data.term )
            self.term = data.term;
        if ( data.parentID )
            self.parentID = data.parentID;
        if ( data.subterms )
        	self.subterms.fromAJAX(data.subterms);
    }

    /**
     * toAJAX
     * - convert to assoc array object for 
     * easy json encoding for ajax
     * @returns object
     */
    this.toAJAX = function(){
        var tmp = {};
            tmp.term = self.term;
            tmp.parentID = self.parentID;
            tmp.subterms = self.subterms.toAJAX();
        return tmp;
    }
    
    /**
     * mkList
     * - make html list view of page
     * @returns string
     */
    this.mkList = function(term){
        var html = '';
        if (term)
        	html+= '<li id="'+term.jsID+'" class="term" data-id="'+term.parent.id+'" data-parentid="'+term.parentID+'">';
        else
        	html+= '<li id="" class="term" data-id="" data-parentid="">';
        html+=      '<span class="name termName">';
        if (term && term.subterms && !term.subterms.isEmpty())
        	html+=      '<span class="showSubTerms closed">&nbsp;</span>';
        if (term)
        	html+=   	term.term;
        html+=      '</span>';
        html+=      '<span class="commands">';
        html+=          '<button class="addSubTerm">+</button>';
        html+=          '<button class="edit">edit</button>';
        html+=          '<button class="del">X</button>';
        html+=      '</span>';
        html+=      '<span class="subterms list hide"><ul>';
        if (term && term.subterms && !self.subterms.isEmpty())
        	html+= self.subterms.mkList();
        html+=      '</ul></span>';
        html+= '</li>';
        return html;
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * 
     */
    this.readForm = function(){
    	var jsID = self.jsID;
    	self = self.readTerm( $('#'+self.jsID) );
    	self.jsID = jsID;
        return self;
    }
    
    /**
     * readTerm
     * - read single Term
     * @param jquery object $term
     */
    this.readTerm = function($term){
    	var term = new TermTYPE(self.$msgBlock);
    	term.term = $term.find('> .name').html();
    	term.subterms = self.readSubTerms( $term.find('> .subterms') );
    	return term;
    }
    
    /**
     * readSubTerms
     * - read sub Terms
     * @param jquery object $subterms
     */
    this.readSubTerms = function($subterms){
    	var subterms = new TermsTYPE(self.$msgBlock);
    	$subterms.find('> ul > li').each(function(){
    		subterms.append( self.readTerm( $(this) ) );
    	})
    	return subterms;
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function(){
        $('#'+self.jsID+' .newTermForm .parentID').val(self.parentID);
        $('#'+self.jsID+' .newTermForm .term').val(self.term);   
    }
    
    /**
     * createNewTermForm
     * - creates a new form for a term 
     */
    this.createForm = function(term){
    	var html = '';
    	html+='<form class="newTermForm" data-prevterm="'+term+'">'
    	html+= 	'<input type="text" class="required term" />';
    	html+= 	'<input type="submit" class="save" value="save"/>';
    	html+= 	'<input type="submit" class="cancel" value="cancel"/>';
    	html+= '</form'
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
                return (suspect == self.parent.id) ? 'equals' : false;
            default:
                return false;
                
        }
        return false;
    }
    
    /**
     * regex
     * - compare two term sizes with regex
     * @param mixed suspect
     * @returns mixed|bool
     */
    this.regex = function( suspect ){
        switch( typeof suspect ){
            case 'string':
                suspect=new RegExp( suspect );
                return suspect.test( self.label.label );
            default:
                return false;
                
        }
        return false;
    }
    
    /** Dom actions **/   
    $('body').on( 'click', '#'+self.jsID+' .edit', function(){
    	var $term = $(this).parents('.term:first');
    	var term = $term.find('> .name').html();
    	$term.find('> .commands').hide();
    	$term.find('> .name').html( self.createForm(term) );
    })
    
    $('body').on( 'click', '#'+self.jsID+' .save', function(){
    	var $form = $(this).parents('.newTermForm:first');
    	var $term = $form.parents('.term:first');
    	var term = $.trim($form.find('.term').val());
    	
    	if (term){
    		$term.find('> .name').html( term );
    	
			self.readForm();
			console.log(self);
			
			if (self.term){
				self.save(function(){
					$('#'+self.jsID).data({
						id: self.parent.id
					});
					$('#'+self.jsID+' > .commands').show();
					console.log($('#'+self.jsID+' > .commands').length)
				});
			}
		}
		else{
			$form.find('.term').css({border: '1px solid red'})
		}	
		
    	return false;
    })
    
    $('body').on( 'click', '#'+self.jsID+' .cancel', function(){
    	var $form = $(this).parents('.newTermForm:first');
    	var $term = $form.parents('.term:first');
    	var term = $form.data().prevterm;
    	
    	if (term)
    		$term.find('> .name').html( term );
    	else
    		$term.remove();
    	
    	return false;
    })
    
    $('body').on( 'click', '#'+self.jsID+' .addSubTerm', function(){
    	var $this = $(this)
    	var $term = $this.parents('.term:first');
    	var $subterms = $term.find('> .subterms'); 
    	$subterms.show();
    	
    	$subterms.find('ul').prepend( self.mkList() );
    	$subterms.find('ul .term:first > .commands .edit').trigger('click');
    })
    
    $('body').on( 'click', '#'+self.jsID+' > .name .showSubTerms', function(){
    	$('#'+self.jsID+' > .subterms').slideToggle();
    	$(this).toggleClass('open').toggleClass('closed');
    })
    
    
    $('body').on('click', '#'+self.jsID+' .del:first', function(){
        new Comfirmation('delete').display(self.jsID+"deleteConfirm", $('#terms'), self.label.label);
        return false;    
    })
    
    $('body').on('click', '#'+self.jsID+'deleteConfirm:first', function(){
        self.del( null, self.$msgBlock, false );
    })
}