<?php
/**
 * SeoTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * SeoTYPE
 * 
 * Handles any seo related data including aliases
 * Put into its own class so that as google modifies
 * its seo requirements, they can all be put in this 
 * class with little additional coding
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class SeoTYPE{
    /**
     * seo title
     * @var string
     * @access public
     */
    public $title;
    
    /**
     * seo description
     * @var string
     * @access public
     */
    public $description;
    
    /**
     * list of aliases
     * @var AliasesTYPE
     * @access private
     */
    private $aliases;
   
    /**
     * constructor
     * can instantiate a tital and description here     
     * @param string $title
     * @param string $description     
     */    
    public function __construct( $title=null, $description=null){
        $this->title=$title;
        $this->description=$description;        
        $this->aliases = new AliasesTYPE();
    }
    
    /**
     * getAliases
     * get Aliases
     * @access public   
     * @return array     
     */    
    public function getAliases(){ return $this->aliases->aliases(); }
    
    /**
     * setAliases
     * set Aliases
     * @access public   
     * @param array $aliases    
     */    
    public function setAliases($aliases){ $this->aliases = $aliases; }
    
    /**
     * getAliasesInstance
     * get instance of aliases
     * @access public   
     * @return AliasesTYPE     
     */    
    public function getAliasesInstance(){ return $this->aliases; }
    
    /**
     * mkInput
     * makes input fields for all seo data
     * @uses SeoTYPE::mkTitleInput
     * @uses SeoTYPE::mkDescriptionInput
     * @uses AliasesTYPE::mkInput
     * @access public   
     * @return string     
     */    
    public function mkInput(){
        $html ='<fieldset id="seoModule" class="seoModule">';
        $html.=     self::mkTitleInput();
        $html.=     self::mkDescriptionInput();
        $html.=     $this->aliases->mkInput();
        $html.='</fieldset>';
        return $html;
    }
    
    /**
     * mkTitleInput
     * makes input field for seo title
     * @access public   
     * @return string     
     */    
    public function mkTitleInput(){
        $html = '<fieldset class="seoModuleTitle">';
        $html.=     '<label for="seoModuleTitleID">Title</label>';
        $html.=     '<input type="text" id="seoModuleTitleID" name="seoModuleTitleID" placeholder="Title" value="'.$this->title.'"/>';
        $html.='</fieldset>';
        return $html;
    }
    
    /**
     * mkDescriptionInput
     * makes input field for seo dsecription
     * @access public   
     * @return string     
     */    
    public function mkDescriptionInput(){
        $html = '<fieldset class="seoDescriptionTitle">';
        $html.=     '<label for="seoModuleDescriptionID">Description</label>';
        $html.=     '<input type="text" id="seoModuleDescriptionID" name="seoModuleDescriptionID" placeholder="Description" value="'.$this->description.'"/>';
        $html.='</fieldset>';
        return $html;
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array(
            'title'             => $this->title,
            'description' => $this->description,
        );
        return $bson + $this->aliases->prepare();
    }

    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if ( isset($bson['title']) )
            $this->title = $bson['title'];
        if ( isset($bson['description']) )
            $this->description = $bson['description'];
        $this->aliases->read($bson);
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->title = $obj->title;               
            $this->description = $obj->description;
            $this->aliases->read($obj);
        }    
    }
    
    /**
     * readJSON
     * converts JSON into Node
     * @access  public
     * @param string|JSON $json
     */
    public function readJSON( $json ){
        $bson = json_decode($json);
        self::read( $bson );
    }
}

?>