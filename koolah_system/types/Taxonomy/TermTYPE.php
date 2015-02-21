<?php
/**
 * TermTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TermTYPE
 * 
 * class tp work with a term
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Terms
 */
class TermTYPE extends Node{
        
    /**
     * label
     * @var string
     * @access public
     */
    public $label;
	
	/**
     * parentID
     * @var string (mongoID)
     * @access public
     */
    public $parentID;
	
	/**
     * order
     * @var int
     * @access public
     */
    public $order;
	
	/**
     * subterms
     * @var TaxonomyTYPE
     * @access private
     */
    private $subterms;
	
    
    /**
     * constructor
     * initiates db to the terms collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, TAXONOMY_COLLECTION );    
        
        $this->label = new LabelTYPE($db, TAXONOMY_COLLECTION);
		$this->parentID = null;
		$this->order = 0;
		$this->subterms = new TaxonomyTYPE($db);
    }
    
	/**
     * getSubterms
     * gets term's subterms
     * @access  public
     * @return termsTYPE
     */
    public function getSubterms(){
        if ($this->subterms->isEmpty()){
			$this->subterms->get(array('parentID'=>$this->id));
		}
		return $this->subterms;
    }
	
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
			'parentID' => $this->parentID,
			'order' => $this->order,
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
        if (array_key_exists('label', $bson))
			$this->label->read($bson);
		if (array_key_exists('parentID', $bson))
            $this->parentID = $bson['parentID'];
		if (array_key_exists('order', $bson))
            $this->order = $bson['order'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label->read($obj);
			$this->parentID = $obj->parentID;
			$this->order = $obj->order;
        }    
    }
}