<?php
/**
 * PageTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PageTYPE
 * 
 * Handles a web page with refrences to all appropriate data
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class PageTYPE extends Node{
        
    /**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * seo information
     * @var SeoTYPE
     * @access public
     */
    public $seo;
    
    /**
     * publication status
     * @var string
     * @access private
     */
    private $publicationStatus;
    
    /**
     * ref to template page uses
     * @var string
     * @access private
     */
    private $templateID;
    
    /**
     * information inside of the page
     * @var mixed
     * @access private
     */
    private $data;
        
    /**
     * constructor
     * initiates db to the menu collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, PAGES_COLLECTION );
        $this->label = new LabelTYPE($db, PAGES_COLLECTION);
        $this->publicationStatus = 'draft';
        $this->templateID = null;
        $this->data = null;
        $this->seo = new SeoTYPE();   
    }
    
    /**
     * getPublicationStatus
     * get PublicationStatus
     * @access public   
     * @return string     
     */    
    public function getPublicationStatus(){ return $this->publicationStatus; }
    
    /**
     * getTemplateID
     * get TemplateID
     * @access public   
     * @return string     
     */    
    public function getTemplateID(){ return $this->templateID; }
    
    /**
     * getData
     * get Data
     * @access public   
     * @return mixed     
     */    
    public function getData(){ return $this->data; }
    
    /**
     * getAliases
     * get Aliases
     * @access public   
     * @return AliasesTYPE     
     */    
    public function getAliases(){ return $this->seo->getAliases(); }
    
    /**
     * setPublicationStatus
     * set PublicationStatus
     * @access public   
     * @param string $status     
     */    
    public function setPublicationStatus($status){ $this->publicationStatus=$status; }
    
    /**
     * setTemplateID
     * set TemplateID
     * @access public   
     * @param string $templateID     
     */    
    public function setTemplateID($templateID){ $this->templateID=$templateID; }
    
    /**
     * setData
     * set Data
     * @access public   
     * @param mixed $data     
     */    
    public function setData($data){ $this->data=$data; }  
    
    /**
     * isPublished
     * check if page is published
     * @access public   
     * @return bool     
     */    
    public function isPublished(){
        return $this->publicationStatus == 'published';
    }
    
    /**
     * save
     * saves object to db
     * calls presave to instantiate anything missing
     * calls postsave to do anything else needed 
     * @uses PageTYPE::preSave
     * @uses PageTYPE::postSave
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */
    public function save($bson=null){
        $status = $this->preSave();
        if ( !$status->success() )
            return $status;
        
        $status = parent::save($bson);     
        if ( !$status->success() )
            return $status;
        
        return $this->postSave();
    }
    
    /**
     * preSave
     * sets the seo title to this page label if no value already exists
     * if no url create url using template's label and the seo title
     * save the aliases 
     * @access  private
     * @return StatusTYPE
     */
    private function preSave(){
        if ( !$this->seo->title ){
            $this->seo->title =  $this->label->label;
        }
        
        if ( !count( $this->getAliases()) ){
            $template = new TemplateTYPE();
            $template->getByID( $this->templateID );
            $url = '/'.$template->label->label.'/'.$this->seo->title;
            $alias = new AliasTYPE(null, $url, $this->getID() );
            $this->seo->getAliasesInstance()->append( $alias );
        }
        
        return $this->saveAliases();
    }
    
    /**
     * postSave
     * fill in with any addition functionality  
     * @access  private
     * @return StatusTYPE
     */
    private function postSave(){
        $status = new StatusTYPE();
        return $status;
    }
    
    /**
     * saveAliases
     * saves all the aliases and removes aliases that mayb have been deleted 
     * @access  private
     * @return StatusTYPE
     */
    private function saveAliases(){
        $status = new StatusTYPE();    
        if ( count($this->getAliases()) ){
            $toDelete = $this->getAliasesToDelete();
            if ( $toDelete->length() )
                $status = $toDelete->del();
            
            if ( $status->success() ){
                foreach( $this->getAliases() as $alias ){
                    if ( !$alias->getID() ){
                        $alias->setPageID( $this->getID() ); 
                        $sStatus = $alias->save();
                        if ( !$sStatus->success() )
                            $status->setFalse( 'errors occurred while saving aliases' );
                    }
                }
            }   
        }
        return $status;
    }
    
    /**
     * getAliasesToDelete
     * compares previous state of aliases and aliases it is being told it now has
     * @access  private
     * @return array
     */
    private function getAliasesToDelete(){
        $toDelete = new AliasesTYPE();;
            
        $origAliases = new AliasesTYPE();
        $origAliases->get( array('pageID'=>$this->getID()) );
        
        if ( !count($origAliases->aliases()) )
            return $toDelete;
        
        $thisAliases = $this->seo->getAliasesInstance();
        if (count($this->getAliases()) ){
            foreach( $origAliases->aliases() as $alias ){
                $nodes = $thisAliases->find( $alias->getAlias() );
                if ( empty($nodes) )
                    $toDelete->append( $alias );
            }
        }
        return $toDelete;    
    }
    
    /**
     * get
     * gets from parent and reads response
     * also gets all of its aliases
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     */    
    public function get( $q, $fields=null ){
        parent::get( $q, $fields );
        $this->fetchAliases();
    }
    
    /**
     * fetchAliases
     * also gets all of its aliases
     * @access public          
     */    
    public function fetchAliases(){
        $this->seo->getAliasesInstance()->get( array( 'pageID'=>$this->getID() ) );
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
           'publicationStatus'=>$this->publicationStatus, 
           'templateID'=>$this->templateID,
           'data'=>$this->data,
           'seo' =>  $this->seo->prepare(),
         );
        return parent::prepare() + $bson + $this->label->prepare();
    }

    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
        parent::read($bson);    
        $this->data = null;
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
        $this->label->read($bson);
        if ( isset($bson['publicationStatus']) )
            $this->publicationStatus = $bson['publicationStatus'];
        if ( isset($bson['templateID']) )
            $this->templateID = $bson['templateID'];
        if ( isset($bson['data']) )
            $this->data = $bson['data'];
        $this->seo->read($bson['seo']);
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label->read($obj->label);    
            $this->publicationStatus = $obj->publicationStatus;               
            $this->templateID = $obj->templateID;
            $this->data = $obj->data;
            $this->seo->read( $bson->seo, $this->getID() );
        }    
    }
}
?>
