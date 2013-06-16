/**
 * @fileOverview defines TemplateSectionsTYPE
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TemplateSectionsTYPE
 * 
 * @author <a href="mailto:cvaugeois@koolah.org">Christophe Vaugeois</a> 
 * @package koolah\cms\public\js\objects\types\templates
 * @class - handles data for a template section(template tab)
 * @constructor
 * @param jQuery dom object $msgBlock
 */
function TemplateSectionsTYPE(){
    
    /**
     * templateSections - array of sizes
     * @type array
     */
    this.templateSections = [];
    
    var self = this;

    /**
     * get_class
     * - return class name
     * @returns string
     */
    this.get_class = function(){ return 'TemplateSectionsTYPE'; }

    /**
     * clear
     * - empties nodes
     */
    this.clear = function(){ self.templateSections = []; }
    
    /**
     * append
     * - appends a node
     * @param mixed node - node to append
     */
    this.append = function( templateSection ){
        if (templateSection instanceof  TemplateSectionTYPE)
            self.templateSections[ self.templateSections.length ] = templateSection;
        else{
            var tmp = new TemplateSectionTYPE();
            tmp.fromAJAX( templateSection );
            self.templateSections[ self.templateSections.length ] = tmp;
        }
    }
    
    /**
     * find
     * - finds suspect in the list
     * @param mixed suspect - suspect to look for
     * @returns mixed|null
     */
    this.find = function( suspect ){ return findInList(self.templateSections, suspect);}
    
    /**
     * count
     * - counts elements
     * @returns int
     */
    this.count = function(){ return self.templateSections.length; }
    
    /**
     * isEmpty
     * - tells you if list is empty
     * @returns bool
     */
    this.isEmpty = function(){ return !Boolean(self.count()); }
    
    /**
     * sections
     * - easy call to get nodes
     * @returns array
     */
    this.sections = function(){ return self.templateSections; }
    
    /**
     * fromAJAX
     * - convert ajax json response into proper Node
     * @param array sections
     */
    this.fromAJAX = function( sections ){
         self.clear();
         if ( sections && sections.length ){
            for (var i=0; i < sections.length; i++){
                var section = new TemplateSectionTYPE(i);
                section.fromAJAX( sections[i] )
                self.append( section );                
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
        var tmp = [];
        if (self.templateSections && !self.isEmpty() ){
            for( var i=0; i < self.count(); i++ )
                tmp[i] = self.templateSections[i].toAJAX();
        }    
        return tmp;    
    }
    
    /**
     * fillForm
     * - fill in a form 
     */
    this.fillForm = function (){
        $('.tab:not(#addSection)').remove();
        if (self.templateSections){
            for( var i=0; i< self.templateSections.length; i++ ){
                self.templateSections[i].fillForm();
            }
        }
    }
    
    /**
     * readForm
     * - read data from form and fill in data
     * @param jQuery dom obj $form - form to read from 
     */
    this.readForm = function( $form ){
        self.clear();
        $form.find('.tab').not("#addSection").each(function(){
            var templateSection = new TemplateSectionTYPE();
            self.append( templateSection.readForm( $(this) ) );    
        })
    }
    
    /**
     * refresh
     * - refresh from form 
     * @param jQuery dom obj $form - form to read from 
     */
    this.refresh = function( $form ){
        var newSections = [];
        $form.find('.tab:not(#addSection)').each(function(){
            newSections[newSections.length]= self.find( $(this).find('a').attr('href') );
        });
        self.templateSections = newSections;
        return self;  
    }
    
    /**
     * remove
     * - removes suspect from the list
     * @param mixed suspect - suspect to look for
     */
    this.remove = function( suspect ){
        var pos = findPosInList(self.templateSections, suspect);
        if (pos>=0)
            self.templateSections.splice(pos, 1);
           
    }
}