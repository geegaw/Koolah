<?php

class SeoTYPE{
    /***
     * Put into its own class so that as google modifies
     * its seo requirements, they can all be put in this 
     * class with little additional coding
     */
    public $title;
    public $description;
    
    private $aliases;
   
    public function __construct( $title=null, $description=null){
        $this->title=$title;
        $this->description=$description;        
        $this->aliases = new AliasesTYPE();
    }
    
    public function getAliases(){ return $this->aliases->aliases(); }
    public function setAliases($aliases){ $this->aliases = $aliases; }
    
    public function getAliasesInstance(){ return $this->aliases; }
    
    public function mkInput(){
        $html ='<fieldset id="seoModule" class="seoModule">';
        $html.=     self::mkTitleInput();
        $html.=     self::mkDescriptionInput();
        $html.=     $this->aliases->mkInput();
        $html.='</fieldset>';
        return $html;
    }
    
    public function mkTitleInput(){
        $html = '<fieldset class="seoModuleTitle">';
        $html.=     '<label for="seoModuleTitleID">Title</label>';
        $html.=     '<input type="text" id="seoModuleTitleID" name="seoModuleTitleID" placeholder="Title" value="'.$this->title.'"/>';
        $html.='</fieldset>';
        return $html;
    }
    
    public function mkDescriptionInput(){
        $html = '<fieldset class="seoDescriptionTitle">';
        $html.=     '<label for="seoModuleDescriptionID">Description</label>';
        $html.=     '<input type="text" id="seoModuleDescriptionID" name="seoModuleDescriptionID" placeholder="Description" value="'.$this->description.'"/>';
        $html.='</fieldset>';
        return $html;
    }
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array(
            'title'             => $this->title,
            'description' => $this->description,
        );
        return $bson + $this->aliases->prepare();
    }

    public function read( $bson ){
        if ( is_array($bson) )
            self::readAssoc($bson);
        elseif( is_object($bson) )
            self::readObj( $bson );
        elseif( is_string($bson) )
            $this->readJSON( $bson );
        else 
            // TODO return error
            return;  
    }
    
    public function readAssoc( $bson ){
//debug::vardump($bson, 1);            
        if ( isset($bson['title']) )
            $this->title = $bson['title'];
        if ( isset($bson['description']) )
            $this->description = $bson['description'];
        $this->aliases->read($bson);
    }
    
    public function readObj( $obj ){
//debug::vardump($obj, 1);        
        if ( $obj ){
            $this->title = $obj->title;               
            $this->description = $obj->description;
            $this->aliases->read($obj);
        }    
    }
    
    public function readJSON( $json ){
        $bson = json_decode($json);
        self::read( $bson );
    }
}

?>