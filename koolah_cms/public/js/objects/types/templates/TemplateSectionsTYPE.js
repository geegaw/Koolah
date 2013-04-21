function TemplateSectionsTYPE(){
    this.templateSections = [];
    
    var self = this;

    /**
     * parent extensions
     */
    /***/
    
    /**
    * methods
    */
    this.sections = function(){ return self.templateSections; }
    
    this.clear = function(){ self.templateSections = []; }
   
    this.append = function( templateSection ){
        if (templateSection instanceof  TemplateSectionTYPE)
            self.templateSections[ self.templateSections.length ] = templateSection;
        else{
            var tmp = new TemplateSectionTYPE();
            tmp.fromAJAX( templateSection );
            self.templateSections[ self.templateSections.length ] = tmp;
        }
    }
    
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
    
    this.toAJAX = function(){
        var tmp = [];
        if (self.templateSections && !self.empty() ){
            for( var i=0; i < self.count(); i++ )
                tmp[i] = self.templateSections[i].toAJAX();
        }    
        return tmp;    
    }
    
    this.fillForm = function (){
        $('.tab:not(#addSection)').remove();
        if (self.templateSections){
            for( var i=0; i< self.templateSections.length; i++ ){
                self.templateSections[i].fillForm();
            }
        }
    }
    
    this.readForm = function( $form ){
        self.clear();
        $form.find('.tab').not("#addSection").each(function(){
            var templateSection = new TemplateSectionTYPE();
            self.append( templateSection.readForm( $(this) ) );    
        })
    }
    
    this.refresh = function( $form ){
        var newSections = [];
        $form.find('.tab:not(#addSection)').each(function(){
            newSections[newSections.length]= self.find( $(this).find('a').attr('href') );
        });
        self.templateSections = newSections;
        return self;  
    }
    
    this.find = function( suspect ){
        return findInList(self.templateSections, suspect);   
    }
    
    this.remove = function( suspect ){
        var pos = findPosInList(self.templateSections, suspect);
        if (pos>=0)
            self.templateSections.splice(pos, 1);
           
    }
    
    this.count = function(){ return self.templateSections.length; }
    this.empty = function(){ return !Boolean(self.count()); }
    /***/

}