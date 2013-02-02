<?php

class TemplateSectionsTYPE extends Nodes
{
    //CONSTRUCT	
    public function __construct( $db, $collection = TEMPLATE_COLLECTION ){
    	parent::__construct( $db, $collection );	
    }
    
    //GETTERS
	public function sections(){ return $this->nodes; }
	
	
	//GETTERS
	public function get( $q=null, $fields=null, $orderBy=array('name'=>1), $distinct=null  ){
		$bsonArray = parent::get( $q, $fields, $orderBy);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$template = new TemplateSectionTYPE( $this->db, $this->collection );
				$template->read( $bson );
				$this->append( $template );
			}
		}	
	}
	
    public function mkTabs(){
        $html = '';    
        if ( $this->length() ){
            foreach( $this->sections() as $section ){
                $html.= '<div class="tab"><a href="#">'.$section->name.'</a></div>';
            }
        }
        return $html;
    }
    
    public function mkInput($page){
        if ( $this->length() ){
            $sectionsMap = null;
            $i=0;
            foreach( $this->sections() as $section ){
                $sectionsMap[$i][] = $section->name;
                $sectionsMap[$i][] = $section->mkInput( $page );
                $i++;
            }
            $sectionsMap[$i][] = 'seo';
            $sectionsMap[$i][] = $page->seo->mkInput();
                
            return htmlTools::mkTabSection($sectionsMap);
        }    
        return '';
        
        
        $html = '';
        if ( $this->length() ){
            foreach( $this->sections() as $section ){
                $html.= '<div id="general" class="section fullWidth active">';
                $html.=     '<div class="sectionBody fullWidth">';
                $html.=         '<div class="fields fullWidth">';
                $html.=             $section->mkInput( $page );
                $html.=         '</div>';
                $html.=     '</div>';
                $html.= '</div> ';
            }
        }
        return $html;
    }
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array( 'sections'=>parent::prepare() );
	}
	
	public function read( $bson ){
	    $this->clear();    
	    if ( $bson ){
	        if (is_string($bson)){
                $bson = json_decode($bson);
                return self::read($bson);
            }
            elseif ( is_array($bson) && isset($bson['sections']) )
    			$sections = $bson['sections'];
            elseif( is_object($bson) )
                $sections = $bson->sections;  	
            else 
                return; //TODO throw error
                
			foreach ( $sections as $section_bson ){
			    $section = new TemplateSectionTYPE( $this->db );
                $section->read($section_bson);
				$this->append( $section );
            }    		
        }
	}
	/***/ 
			   
} 