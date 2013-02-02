<?php

class PermissionTYPE extends Node {
	
	private $label;
	
	public function __construct( $db ){
		parent::__construct( $db, PERMISSIONS_COLLECTION );
		$this->label = new LabelTYPE($db, PERMISSIONS_COLLECTION);
	}
	
	/***
	 * GETTERS
	 */
	public function getLabel(){ return $this->label->label; }
	public function getRef(){ return $this->label->label.'_'.$this->label->getRef(); }
    /***/
    
    /***
	 * SETTERS
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
	/***/
	
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
	
	/***
	 * BOOLS
	 */	
	/***/
	
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return parent::prepare() + $this->label->prepare();		
	}
	
	public function read( $bson ){
		parent::read($bson);
		$this->label->read( $bson );		
	}
	/***/
	
}
/***/

?>
	