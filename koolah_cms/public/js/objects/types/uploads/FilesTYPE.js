/**
 * @fileOverview defines FilesTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FilesTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\uploads
 * @extends Nodes
 * @class - works with multiple files
 * @constructor
 * @param jQuery dom object $el
 */
function FilesTYPE($msgBlock){
    
    /**
     * parent - extend Nodes
     *@type Nodes
     */
    this.parent = new Nodes( 'KoolahFiles' );
    
    /**
     * $msgBlock - dom reference to where to display messages
     *  @type jQuery dom object
     */
    this.$msgBlock = $msgBlock;
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'FilesTYPE'; }
    
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
    this.append = function( file ){ self.parent.append( file ); }
    
    /**
     * get
     * - gets a node by id and classname stored internally
     * display status upon error
     * @param string callback - function name
     * @param jQuery dom object $el - where the message will be displayed
     * @param bool aync
     */
    this.get = function( callback, args, $el, async ){
        if (!$el)
            $el = self.$msgBlock; 
        self.parent.get( self.fromAJAX, callback, args, $el, async ); 
    }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.files(), suspect); }
    
    /**
     * filter
     * - filters a list, can use regex or exact max
     * @param mixed suspect - suspect to filter by
     * @param string by - regex|exact
     * @returns array
     */
    this.filter = function( suspect, by ){ 
        var results = new FilesTYPE( self.$msgBlock );
        results.parent.nodes = filterList( self.files(), suspect, by ); 
        return results;
    }
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.files().length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * files
     * - easy call to get nodes
     * @returns array
     */
    this.files = function(){ return self.parent.nodes; }
    //*** /parent extensions ***//
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array response
     */
    this.fromAJAX = function( response ){
        self.clear();
        if ( response.nodes && response.nodes.length ){
             for( var i=0; i < response.nodes.length; i++ ){
                 var data = response.nodes[i];
                 var file = new FileTYPE(self.$msgBlock);
                 file.fromAJAX( data );
                 self.append( file );
            }   
        }
    }
    
    /**
     * getDropdownList
     * - get drop down list for jQuery autocomplete 
     * @param obj obj
     * @returns array
     */
    this.getDropdownList = function( obj ){
        var list = [];
        if ( self.files() ){
            for (var i=0; i < self.files().length; i++){
                var file = self.files()[i];
                if ( obj ){
                    var dropDown = {}
                    dropDown.label = file.label.label;
                    dropDown.value  = file.parent.id;
                }
                else
                    dropDown = file.label.label;
                list[ list.length ] = dropDown;   
            }
        }
        return list;
    }
    
    /**
     * getExtDropdownList
     * - get drop down list of extensions for jQuery autocomplete 
     * @param obj obj
     * @param bool onlyWStyle - just show label
     * @returns array
     */
    this.getExtDropdownList = function( obj ){
        var list = [];
        if ( self.files() ){
            for (var i=0; i < self.files().length; i++){
                var file = self.files()[i];
                if ( obj ){
                    var dropDown = {}
                    dropDown.label = file.ext;
                    dropDown.value  = file.parent.id;
                }
                else
                    dropDown = file.ext;
                list[ list.length ] = dropDown;   
            }
        }
        return list;
    }
    
    /**
     * mkList
     * - make html list of pages
     * @param object params - pod options
     * @param object tagParams - pod options for tags
     * @returns string
     */
    this.mkList = function( params, tagParams ){
        var html = '';
        if ( self.files() ){
            for (var i=0; i < self.files().length; i++){
                var file = self.files()[i];
                html += file.mkList( params, tagParams );   
            }
        }
        return html;
    }
    
    /**
     * filterByType
     * - filters a list by its type - ex by image, by video, etc
     * @param mixed suspect - suspect to filter by
     * @returns array
     */
    this.filterByType = function( suspect ){ 
        var results = new FilesTYPE( self.$msgBlock );
        var comparison = 'is'+suspect;
        
        for ( var i = 0; i < self.files().length; i++ ){
            var file = self.files()[i];
            if ( eval( 'file.'+comparison+'()' ) )
                results.append( file );    
        }
         
        return results;
    }
    
    /**
     * filterByExt
     * - filters a list by its extension
     * @param mixed suspect - suspect to filter by
     * @returns array
     */
    this.filterByExt = function( suspect ){ 
        suspect = suspect.toLowerCase();
        
        var results = new FilesTYPE( self.$msgBlock );
        
        for ( var i = 0; i < self.files().length; i++ ){
            var file = self.files()[i];
            if ( file.ext == suspect )
                results.append( file );    
        }         
        return results;
    }
    
    /**
     * filterByTag
     * - filters a list by its tag
     * @param mixed suspect - suspect to filter by
     * @returns array
     */
    this.filterByTag = function( suspect ){ 
        var results = new FilesTYPE( self.$msgBlock );
        
        for ( var i = 0; i < self.files().length; i++ ){
            var file = self.files()[i];
            if ( file.tags.tags().length ){
                var hasTag = false;
                for ( var j=0; j < file.tags.tags().length && !hasTag; j++){
                    var tag = file.tags.tags()[j];
                    if ( tag.regex( suspect ) ){
                        hasTag = true;
                        results.append( file );           
                    }
                }
            }       
        }         
        return results;
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.files(), suspect);
        if (pos>=0)
            self.files().splice(pos, 1);
    }
}