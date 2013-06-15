<?php
/**
 * FieldTypeTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
 /**
 * FieldTypeTYPE
 * 
 * Class to handle a field based on what type it is
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Templates
 */
class FieldTypeTYPE{
	
	/**
     * type
     * @var string
     * @access public
     */
    public $type;
	
	/**
     * constructor
     * can set a type
     * @param string $type
     */    
    public function __construct( $type='text' ){
		if ( in_array( $type, $this->getTypes() ) )
            $this->type = $type;
        else
            $this->type = 'text';	
	}
	
	/**
     * mkInput
     * make an input based on the field, fill with page data if possible
     * @access public   
     * @param PageTYPE $page
     * @param FieldTYPE $field     
     * @return string
     */    
    public function mkInput( $page, $field){
        $pageData = $page->getData();
	    $html = '';
	    if ( $field->many ){
	        $html = '<fieldset class="field many fullWidth '.$this->type.'">';    
	        $html.=    '<a href="#" class="many">+</a>';
	        $html.=    '<input type="hidden" class="manyRef noreset" value="'.$field->getRef().'" />';
            
            if (isset($pageData[$field->getRef()]) && !empty($pageData[$field->getRef()])){
                foreach( $pageData[$field->getRef()] as $data )
                    $html.= $this->mkManyInput($data, $field);
            }
            else
                $html.= $this->mkManyInput($pageData, $field);
            $html.= '</fieldset>';
	    }
        else        
            $html.= $this->handleType( $pageData, $field );
            
        return $html;
	}
	
    
    /**
     * handleType
     * based on the type, create a differnt input
     * @access private   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    private function handleType( $pageData, $field ){
	    switch ( $this->type ){
            case 'paragraph':
                $html = $this->mkParagraphInput( $pageData, $field );
                break;
            case 'dropdown':
                $html = $this->mkDropdownInput( $pageData, $field );
                break; 
            case 'file':
                $html = $this->mkFileInput( $pageData, $field );
                break;
            case 'date':
                $html = $this->mkDateInput( $pageData, $field );
                break;   
            case 'query':
                $html = $this->mkQueryInput( $pageData, $field );
                break;                    
            case 'custom':
                $html = $this->mkCustomInput( $pageData, $field );
                break;
            default:
                $html = $this->mkTextInput( $pageData, $field );
                break;
        }
        return $html;
	}
	
	/**
     * mkTextInput
     * make input for a text field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkTextInput( $pageData, $field ){
	    $val = '';    
	    if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]))
            $val = $pageData[$field->getRef()];
        $html = '<fieldset class="field fullWidth">';
        $required = $this->getRequiredClass( $field );
        $html.=     '<label for="'.$field->getRef().'">'.$field->getLabel().'</label>';
        $html.=     '<input type="text" id="'.$field->getRef().'" class="'.$required.'" placeholder="'.$field->getLabel().'" value="'.$val.'"/>';
        $html.= '</fieldset> ';
        return $html;
    }
	
	/**
     * mkParagraphInput
     * make input for a paragraph field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkParagraphInput( $pageData, $field ){
	    $val = '';    
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]) )
            $val = $pageData[$field->getRef()];
	    $html = '';
        $html = '<fieldset class="field fullWidth">';
        $required = $this->getRequiredClass( $field );
        $html.=     '<label for="'.$field->getRef().'">'.$field->getLabel().'</label>';
        $html.=     '<textarea id="'.$field->getRef().'" class="wysiwyg '.$required.'">'.$val.'</textarea>';
        $html.= '</fieldset>';
        return $html;
	}
    
    /**
     * mkDropdownInput
     * make input for a dropdown field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkDropdownInput( $pageData, $field ){
        $val = '';    
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]) )
            $val = $pageData[$field->getRef()];
        $html = '<fieldset class="field fullWidth">';
        $required = $this->getRequiredClass( $field );
        $html.=     '<label for="'.$field->getRef().'">'.$field->getLabel().'</label>';
        $html.=     '<select id="'.$field->getRef().'" class="'.$required.'">';
        $html.=         '<option value="no_selection">'.$field->getLabel().'</option>';
        
        if ( $field->options){
            $options = explode( "\n", $field->options );
            foreach( $options as $option ){
                if ( $val == $option )    
                    $html.= '<option value="'.$option.'" selected="selected">'.$option.'</option>';
                else
                    $html.= '<option value="'.$option.'">'.$option.'</option>';
            }
        }
        $html.=     '</select>';
        $html.= '</fieldset>';
        return $html;
    }
    
    /**
     * mkFileInput
     * make input for a file upload field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkFileInput( $pageData, $field ){
        $val = '';    
        $file = new FileTYPE(); 
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()])){
            $val = $pageData[$field->getRef()];
            $file->getByID( $val );
        }
        else
            $file->label->label = '';
        
        $html = '<fieldset class="field fileField" data-type="'.$field->options.'" data-ref="'.$field->getRef().'">';
        $required = $this->getRequiredClass( $field );
        $html.=     '<label for="'.$field->getRef().'">'.$field->getLabel().'</label>';
        $html.=     '<input type="text" id="'.$field->getRef().'Label" class="fileLabel" placeholder="'.$field->getLabel().'" value="'.$file->label->label.'" disabled/>';
        $html.=     '<input type="hidden" id="'.$field->getRef().'" class="'.$required.' fileID" value="'.$file->getID().'"/>';
        $html.=     '<button type="button" class="'.trim('selectFile '.$field->options).'">Select</button>';
        $html.= '</fieldset>';
        return $html;
    }

    /**
     * mkDateInput
     * make input for a date field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkDateInput( $pageData, $field ){
        $val = '';    
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]))
            $val = $pageData[$field->getRef()];
        
        $html = '<fieldset class="field dateField" data-ref="'.$field->getRef().'">';
        $required = $this->getRequiredClass( $field );
        $html.=     '<label for="'.$field->getRef().'">'.$field->getLabel().'</label>';
        $html.=     '<input type="text" id="'.$field->getRef().'" class="datePicker '.$required.'" placeholder="'.$field->getLabel().'" value="'.$val.'"/>';
        $html.= '</fieldset>';
        return $html;
    }
    
    /**
     * mkQueryInput
     * make input for a query field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkQueryInput( $pageData, $field ){
        $val = '';    
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]))
            $val = $pageData[$field->getRef()];
        
        $query = new QueryTYPE();
        $query->read($field->options);
        $options = $query->execute();
        $required = $this->getRequiredClass( $field );
        
        $html = '<fieldset class="field queryField" data-ref="'.$field->getRef().'">';
        $html.=     '<label for="'.$field->getRef().'">'.$field->getLabel().'</label>';
        $html.=     '<select id="'.$field->getRef().'" class="'.$required.'" autocomplete="off">';
        $html.=         '<option value="no_selection">'.$field->getLabel().'</option>';
        
        if ( $options){
            foreach( $options as $option ){
                if ( $val == $option->getID() )    
                    $html.= '<option value="'.$option->getID().'"  selected>'.$option->label->label.'</option>';
                else
                    $html.= '<option value="'.$option->getID().'">'.$option->label->label.'</option>';
            }
        }
        $html.=     '</select>';
        $html.= '</fieldset>';
        return $html;
    }

    /**
     * mkCustomInput
     * make input for a custom field, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkCustomInput( $pageData, $field ){
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]) )
            $pageData = $pageData[$field->getRef()];

        $html = '';
        $template = new TemplateTYPE();
        $template->getByID( $field->options );
        
        $html = '<fieldset class="field fullWidth custom">';    
        $html.=    '<input type="hidden" class="customRef noreset" value="'.$field->getRef().'" />';
        $html.=    '<input type="hidden" class="customRefData noreset" value=\''.json_encode($pageData).'\' />';
        $html.=     $template->mkInput($pageData, true);
        $html.= '</fieldset>';
        return $html;
    }
    
    /**
     * mkManyInput
     * make input frame for inputs that can have multiple values, fill with page data if possible
     * @access public   
     * @param mixed $pageData
     * @param FieldTYPE $field
     * @return string     
     */    
    public function mkManyInput( $pageData, $field ){
        $html = '';
        $html.='<fieldset class="manyBody collapsible removableBody">';
        $html.=     '<div class="commandBar">'; 
        $html.=         '<h3>'.$field->getLabel().'</h3>';
        $html.=         '<button type="button" class="removable\">X</button>';
        $html.=         '<button type="button" class="toggle open">&#8211;</button>';
        $html.=     '</div>';
        $html.=     '<fieldset class="collapsibleBody">';
        $html.=         $this->handleType( $pageData, $field );
        $html.=     '</fieldset>';
        $html.='</fieldset>';
        return $html;
    }
    
    /**
     * getRequiredClass
     * helper to return a required class for required fields
     * @access private   
     * @param FieldTYPE $field
     * @return string     
     */    
    private function getRequiredClass( $field ){
        if ( $field->required )
            return 'required';
        else
            return '';   
    }
	
	/**
     * getTypes
     * helper to return valid input types
     * NOTE: if adding types also must add in FieldTypeTYPE.js
     * @access private   
     * @return array     
     */    
    public static function getTypes(){
		$types = array(
					'text', 
					'paragraph',
					'dropdown',
					'query',
					'file',
					'date',
					'custom',
				);
		return $types;
	}
}
?>