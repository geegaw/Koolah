<?php
/**
 * TemplateTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * TemplateTYPE
 * 
 * Root class to handle koolah templates
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Templates
 */
class TemplateTYPE extends Node{
    	
	/**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * sections in the template
     * @var TemplateSectionsTYPE
     * @access public
     */
    public $sections;
	
	/**
     * type of template
     * @var string
     * @access private
     */
    private $templateType = null;
	
    /**
     * constructor
     * initiates db to the templates collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		parent::__construct( $db, TEMPLATE_COLLECTION );	
		$this->label = new LabelTYPE( $db, TEMPLATE_COLLECTION );
		$this->sections = new TemplateSectionsTYPE( $db );
	}
    
    /**
     * getType
     * get Type
     * @access public   
     * @return string     
     */    
    public function getType(){ return $this->templateType; }
    
    /**
     * setType
     * set Type
     * @access public   
     * @param string $type     
     */    
    public function setType($type){
         if ( in_array( $type, self::getTypes() ) )    
            $this->templateType = $type; 
    }
    
    /**
     * mkInput
     * make an input for the template, fill with page data if possible
     * @access public   
     * @param PageTYPE $page
     * @param bool $custom     
     * @return string
     */    
    public function mkInput( $page=null, $custom=false ){
        if ( !$page ){
            $page = new PageTYPE();
        }

        if ( !$custom ){    
            $html = '<form id="'.$this->label->getRef().'" class="newPageWidgetForm fullWidth" method="post" action="#">';
            $html.=     '<fieldset class="newPageWidgetNameFieldset fullWidth">';
            $html.=         '<label for="newPageWidgetName">Page Name</label>';
            $html.=         '<input type="text" id="newPageWidgetName" name="newPageWidgetName" value="'.$page->label->label.'" placeholder="Page Name" class="required"/>';
            $html.=     '</fieldset>';
            $html.=     $this->sections->mkInput($page);
            $html.= '<form>';
        }
        else{
            $sections = $this->sections->sections();
            $section = $sections[0];
            $html = $section->mkInput($page);
        }    
           
        return $html;
    }

   /**
     * mkWorkflow
     * make an input for the workflow
     * @access public   
     * @return string
     */    
    public function mkWorkflow(){
        //TODO make variable
        $html = '<fieldset class="workflowOptions fullWidth">';
        $html.=     '<label for="'.$this->label->getRef().'">Change Workflow to:</label>';
        $html.=     '<select id="'.$this->label->getRef().'" class="workflowOptions">';
        $html.=         '<option value="no_selection"></option>';
        $html.=         '<option value="draft">Draft</option>';
        $html.=         '<option value="approval">Ready for Approval</option>';
        $html.=         '<option value="scheduled">Schedule</option>';
        $html.=         '<option value="published">Published</option>';
        $html.=     '</select>';
        $html.= '</fieldset>';
        return $html;
    }
    
    /**
     * save
     * set label ref if insert
     * @access public   
     * @param assocArray $bson
     * @return StatusTYPE
     */    
    public function save($bson=null ){
		if ( !$this->id )	
			$this->label->setRef();
		return parent::save($bson);
	}
	
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		$bson = array('templateType'=>$this->templateType);
		return parent::prepare() + $bson + $this->label->prepare() + $this->sections->prepare();		
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
        if ( isset($bson['templateType']) )
            $this->templateType = $bson['templateType'];
        $this->label->read( $bson );
        $this->sections->read($bson);            
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->templateType = $obj->templateType;            
            $this->label->read( $obj->label );
            $this->sections->read($obj->sections);
        }        
    }
    
	/**
     * getTypes
     * helper to return valid template types
     * NOTE:  if adding types also must add in TemplateTYPE.js 
     * @access private   
     * @return array     
     */    
    public static function getTypes(){
		$types = array(
					'page', 
					'widget',
					'field'
				);
		return $types;
	}
}
?>
