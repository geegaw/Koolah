<?php

class TemplateTYPE extends Node{
    	
	//PUBLIC
    public $label;
    public $sections;
	
	//PRIVATE
	private $templateType = null;
	
    //CONSTRUCT
    public function __construct( $db=null ){
		parent::__construct( $db, TEMPLATE_COLLECTION );	
		$this->label = new LabelTYPE( $db, TEMPLATE_COLLECTION );
		$this->sections = new TemplateSectionsTYPE( $db );
	}
    
    public function getType(){ return $this->templateType; }
    public function setType($type){
         if ( in_array( $type, self::getTypes() ) )    
            $this->templateType = $type; 
    }
    
    public function mkInput( $page=null, $custom=false ){
        if ( !$page ){
            $page = new PageTYPE();
        }
//debug::printr($this, true);
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
    
    
	public function save($bson=null ){
		if ( !$this->id )	
			$this->label->setRef();
		return parent::save($bson);
	}
	
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = array('templateType'=>$this->templateType);
		return parent::prepare() + $bson + $this->label->prepare() + $this->sections->prepare();		
	}

    public function read( $bson ){
//debug::printr($bson, true);     
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
    
    public function readAssoc( $bson ){
//debug::printr($bson);
        if ( isset($bson['templateType']) )
            $this->templateType = $bson['templateType'];
        $this->label->read( $bson );
        $this->sections->read($bson);            
    }
    
    public function readObj( $obj ){
        if ( $obj ){
//debug::printr($obj, true);            
            $this->templateType = $obj->templateType;            
            $this->label->read( $obj->label );
            $this->sections->read($obj->sections);
        }        
    }
    
	/***
	 * Helpers
	 */
	
	/***
     *
     *  
     * NOTE: if adding types also must add in TemplateTYPE.js 
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
