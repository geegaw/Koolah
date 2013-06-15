<?php
/**
 * PermissionTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PermissionTYPE
 * 
 * Class to work with a permission
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\RolesPermissions
 */
class PermissionTYPE extends Node {
	
	/**
     * label
     * @var LabelTYPE
     * @access private
     */
    private $label;
	
	/**
     * constructor
     * initiates db to the permission collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		parent::__construct( $db, PERMISSIONS_COLLECTION );
		$this->label = new LabelTYPE($db, PERMISSIONS_COLLECTION);
	}
	
	/**
     * getLabel
     * get Label
     * @access public   
     * @return string     
     */    
    public function getLabel(){ return $this->label->label; }
	
	/**
     * getRef
     * get Ref
     * @access public   
     * @return string     
     */    
    public function getRef(){ return $this->label->label.'_'.$this->label->getRef(); }
    /***/
    
    /**
     * set
     * set label based on permission passed
     * @access public   
     * @param string $permission     
     */    
    public function set( $permission ){
		$parts = explode( '_', $permission );
		$last = count($parts)-1;
		$ref = $parts[$last];
		unset( $parts[$last] );
		$label = implode( '_', $parts );
		
		$this->label->label = $label;
		$this->label->setRef( $ref );
	}
	
	/**
     * mkInput
     * render html input for a permission
     * @access public   
     * @param string $fieldsetClass -- optional
     * @param string $checkboxClass -- optional
     * @return string      
     */    
    public function mkInput( $fieldsetClass='', $checkboxClass='' ){
		$html = '';
		if ( $this->label->label ){	
			$html .= '<fieldset class="'.$fieldsetClass.'">';
			$html .= 	'<input type="checkbox" id="'.$this->getRef().'" class="'.$checkboxClass.'" value="'.$this->getRef().'" />';
			$html .= 	'<label for="'.$this->getRef().'">'.$this->getLabel().'"</label>';
			$html .= '<fieldset>';
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
		return parent::prepare() + $this->label->prepare();		
	}
	
	/**
     * read
     * reads from db - clears object ahead of time
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		parent::read($bson);
		$this->label->read( $bson );		
	}
}
?>
	