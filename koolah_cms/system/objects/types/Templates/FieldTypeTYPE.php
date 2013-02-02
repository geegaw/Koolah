<?php
class FieldTypeTYPE{
	
	public $type;
	
	public function __construct( $type='text' ){
		if ( in_array( $type, $this->getTypes() ) )
            $this->type = $type;
        else
            $this->type = 'text';	
	}
	
	public function mkInput( $pageData, $field){
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
	
    
    /***
	 * Helpers
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
            case 'custom':
                $html = $this->mkCustomInput( $pageData, $field );
                break;
            default:
                $html = $this->mkTextInput( $pageData, $field );
                break;
        }
        return $html;
	}
	
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

    public function mkCustomInput( $pageData, $field ){
        if( isset( $pageData[$field->getRef()]) && !empty($pageData[$field->getRef()]) )
            $pageData = $pageData[$field->getRef()];

        $html = '';
        $template = new TemplateTYPE();
        $template->getByID( $field->options );
        
        $html = '<fieldset class="field fullWidth custom">';    
        $html.=    '<input type="hidden" class="customRef noreset" value="'.$field->getRef().'" />';
        //debug
        $html.=    '<input type="hidden" class="customRefData noreset" value=\''.json_encode($pageData).'\' />';
        $html.=     $template->mkInput($pageData, true);
        $html.= '</fieldset>';
        return $html;
    }
    
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
    
    private function getRequiredClass( $field ){
        if ( $field->required )
            return 'required';
        else
            return '';   
    }
	
	/***
     *
     *  
     * NOTE: if adding types also must add in FieldTypeTYPE.js 
     */
	public static function getTypes(){
		$types = array(
					'text', 
					'paragraph',
					'dropdown',
					'file',
					'date',
					'custom',
				);
		return $types;
	}
	
}
?>