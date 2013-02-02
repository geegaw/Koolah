function seoTYPE() {
    this.parent = new Node('PageTYPE');

    this.title = '';
    this.description = '';
    this.aliases = new AliasesTYPE( $('#seoModule .aliases:first') );
    
    var self = this;

    /**
     * methods
     */
    this.fromAJAX = function(data) {
        self.aliases.fromAJAX(data.aliases);
        self.title = data.title;
        self.description = data.description;
    }

    this.toAJAX = function() {
        var tmp = {};
        tmp.aliases = self.aliases.toAJAX();
        tmp.title = self.title;
        tmp.description = self.description;
        return tmp;
    }

    this.mkInput = function() {
        var html = '';
        return html;
    }

    this.readForm = function($form) {
        self.title = $('#seoModuleTitleID').val();
        self.description = $('#seoModuleDescriptionID').val();
        self.aliases.readForm($form);
    }

    this.fillForm = function(){
        $('#seoModuleTitleID').val( self.title );
        $('#seoModuleDescriptionID').val( self.description );
        self.aliases.fillForm();
    }
    /***/

}