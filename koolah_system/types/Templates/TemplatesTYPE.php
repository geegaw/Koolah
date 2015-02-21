<?php
/**
 * TemplatesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * TemplatesTYPE
 * 
 * Extends Nodes to work with TemplateTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Pages
 */
class TemplatesTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the templates collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = TEMPLATE_COLLECTION ){
    	parent::__construct( $db, $collection );	
    }
    
    /**
     * templates
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function templates(){ return $this->nodes; }
	
	/**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $offset=0, $limit=null, $distinct=null  ){
		$bsonArray = parent::get( $q, $fields , $orderBy, $offset, $limit, $distinct);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$template = new TemplateTYPE( $this->db, $this->collection );
				$template->read( $bson );
				$this->append( $template );
			}
		}	
	}
    
    /**
     * getType
     * get all templates of type
     * @access  public
     * @param string $type
     */
    public function getType( $type ){ self::get( array('templateType'=>$type) );}
    
    /**
     * getPageTemplates
     * get all templates of type page
     * @access  public
     */
    public function getPageTemplates(){ $this->getType('page'); } 
	
	/**
     * getWidgetTemplates
     * get all templates of type widget
     * @access  public
     */
    public function getWidgetTemplates(){ $this->getType('widget'); }
    
    /**
     * getFieldTemplates
     * get all templates of type field
     * @access  public
     */
    public function getFieldTemplates(){ $this->getType('field'); }
	
	/**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		if ( $bson && isset($bson['fields']) ){
			$this->clear();			
			foreach ( $bson['fields'] as $node )
				$this->append($node);
		}						
	}
} 