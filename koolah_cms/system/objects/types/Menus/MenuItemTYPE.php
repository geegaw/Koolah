<?php

class MenuItemTYPE extends Node {
	const debug = 0;
	
	public $name;
	public $url;
	public $submenu;
	
	private $permission;
	
	
	public function __construct( $db ){
		
		parent::__construct( $db, MENU_COLLECTION );
		$this->name = '';
		$this->url = '';
		$this->submenu = new MenuItemsTYPE( $db );
		$this->permission = '';			
	}
	
	public function getPermission(){ return $this->permission; }
	
	public function display( $active='', $wrapper='li', $rec=false, $class='', $levels=-1 ){
		
		$requiresSub = false;
		if ( $rec && $levels != 0 && $this->submenu->isNotEmpty()  )
			$requiresSub = true;
		
		if ( !is_array( $active ) )	
			$active = array( $active );
		$act = '';
		if ( in_array( $this->name, $active ))
			$act = ' active';
		
		echo '<'.$wrapper.' class="menuItem '.$class.'">';
		echo 	'<a href="'.MenuItemTYPE::formatUrl( $this->url ).'" class="';
		if ($requiresSub){
			echo 'subMenuTrigger';	
		}
		echo $act.'">'.$this->name.'</a>';
		
		if ($requiresSub){
			$levels--;
			
			if ( self::debug )
				echo '<div class="subMenu">';
			else
				echo '<div class="subMenu hide">';
			$this->submenu->display($active, $wrapper, $rec, $class, $levels );
			echo '</div>';
		}
				
		echo "</$wrapper>";
	}
	
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = array(
			'name'=>$this->name,		
			'url'=> $this->url,	
			'permission'=>$this->permission,
		);
		return parent::prepare() + $bson + $this->submenu->prepare();		
	}
	
	public function read( $bson ){
		parent::read($bson);
		if ( isset($bson['name']) )
			$this->name = $bson['name'];
		if ( isset($bson['url']) )
			$this->url = $bson['url'];
		if ( isset($bson['permission']) )
			$this->permission = $bson['permission'];
		$this->submenu->read( $bson );		
	}
	/***/
	
	/***
	 * Helpers
	 */	 
	 static public function formatUrl( $url ){
	 	if ( $url && is_string($url)){
	 		return $url;
	 	}
		else
			return '#';
	 }
	 /***/
}

?>
	