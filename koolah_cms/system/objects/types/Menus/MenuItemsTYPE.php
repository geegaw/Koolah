<?php

class MenuItemsTYPE extends Nodes {
		
	public $status;
		
	private $sessionUser;
	
	public function __construct( $db ){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )		
			parent::__construct( $db, MENU_COLLECTION );				
	}
	
	public function menuItems(){ return $this->nodes; }
	
	public function display( $active='', $wrapper='li', $rec=false, $class='', $levels=-1 ){
		if ( $this->menuItems() ){
			if ( !is_array( $active ) )	
				$active = array( $active );
			foreach ( $this->menuItems() as $menuItem ){
				if ( !$menuItem->getPermission() || ( $this->sessionUser->can($menuItem->getPermission()) ))
					$menuItem->display( $active, $wrapper, $rec, $class, $levels );
			}
		}			
	}
	
	
	/***
	 * BOOLS
	 */	
	public function isNotEmpty(){return (bool)$this->numNodes; }
	public function isEmpty(){  return !$this->isNotEmpty(); }
	/***/
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array('menuItems'=>parent::prepare() );		
	}
	/***/
	
	public function read( $bson ){
		//parent::read($bson);
		if ( isset($bson['menuItems']) ){
			foreach ( $bson['menuItems'] as $menuItem ){
				$tmp = new MenuItemTYPE( $this->db );
				//debug::printr( $menuItem, 1 );
				$tmp->read( $menuItem );				
				$this->append( $tmp );	
			}			
		}					
	}	
}

?>
	