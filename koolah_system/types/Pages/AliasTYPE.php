<?php
/**
 * AliasTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * AliasTYPE
 * 
 * Handles a web alias
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class AliasTYPE extends Node{
        
    /**
     * url alias
     * @var string
     * @access protected
     */
    protected $alias;
        
    /**
     * ref to page alias belongs to
     * @var string
     * @access private
     */
    private $pageID;
    
    /**
     * constructor
     * initiates db to the menu collection
     * can instantiate an alias and pageID     
     * @param customMongo $db
     * @param string $alias
     * @param string $pageID     
     */    
    public function __construct( $db=null, $alias=null, $pageID=null ){
        parent::__construct( $db, ALIAS_COLLECTION );   
        $this->alias = self::mkSafe( $alias );
        $this->pageID = $pageID;
    }
    
    /**
     * getAlias
     * get Alias
     * @access public   
     * @return string     
     */    
    public function getAlias(){ return $this->alias; }
    
    /**
     * getPageID
     * get PageID
     * @access public   
     * @return string     
     */    
    public function getPageID(){ return $this->pageID; }
    
    /**
     * setAlias
     * set Alias
     * @access public   
     * @param string $alias     
     */    
    public function setAlias( $alias ){ $this->alias = self::mkSafe( $alias ); }
    
    /**
     * setPageID
     * set PageID
     * @access public   
     * @param string $pageID     
     */    
    public function setPageID( $pageID ){ $this->pageID = $pageID; }  
    
    /**
     * compare
     * compares a suspect to current url
     * @access public   
     * @param string $suspect
     * @return bool     
     */    
    public function compare( $suspect ){
        return $suspect == $this->alias; 
    }
    
    /**
     * mkCapsule
     * makes an editable html pod with the alias
     * @access public   
     * @return string     
     */    
    public function mkCapsule(){
        if ( $this->alias )
            return htmlTools::mkPod( $this->getID(), $this->alias, 'alias'.uniqid(), 'alias', array('editable'=>true) );
        return '';
    }
    
    /**
     * save
     * saves object to db
     * verifies alias does not already exist 
     * @uses AliasTYPE::exists
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE
     */
    public function save($bson=null){
        if( !$this->exists() )
            return parent::save();
        else{
            $status = new StatusTYPE('alias already exists', false);
            return $status;
        }
    }
    
    /**
     * exists
     * checks if alias already exists 
     * @uses AliasTYPE::exists
     * @access  public
     * @param AliasTYPE $suspect -- optional
     * @return bool
     */
    public function exists( $suspect=null ){
        if (!$suspect)
            $suspect = $this->alias;
        $checker = new AliasTYPE( $this->db );
        $checker->get( array( 'alias'=>$suspect )  );
        return $checker->getID();
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
           'alias'=>$this->alias, 
           'pageID'=>$this->pageID
         );
        return parent::prepare() + $bson;
    }

    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
        parent::read( $bson );    
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
        if ( isset($bson['alias']) )
            $this->alias = $bson['alias'];
        if ( isset($bson['pageID']) )
            $this->pageID = $bson['pageID'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            if (property_exists($obj, 'alias'))
                $this->alias = $obj->alias;        
            if (property_exists($obj, 'pageID'))       
                $this->pageID = $obj->pageID;
        }    
    }
    
    /**
     * mkSafe
     * helper - lowercases string, removes spaces
     * urlencodes string
     * @access  public
     * @param string $str
     * @return string
     */
    static public function mkSafe( $str ){
        $str = strtolower($str);
        $str = str_replace( ' ', '_', $str);
        return urlencode($str);
    }
}

?>
