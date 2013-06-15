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
class TemplateSectionsTYPE extends Nodes{
   
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
     * sections
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function sections(){ return $this->nodes; }
	
	/**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by label asc
     * @param bool $distinct        
     */    
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
	
    /**
     * mkTabs
     * make the tabs for the sections
     * @access  public
     * @return string
     */
    public function mkTabs(){
        $html = '';    
        if ( $this->length() ){
            foreach( $this->sections() as $section ){
                $html.= '<div class="tab"><a href="#">'.$section->name.'</a></div>';
            }
        }
        return $html;
    }
    
    /**
     * mkInput
     * make input for a section including the seo inputs
     * @access  public
     * @param PageTYPE $page
     * @return string
     */
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
	
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		return array( 'sections'=>parent::prepare() );
	}
	
	/**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
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
} 