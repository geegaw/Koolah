<?php
/**
 * MenuItemTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MenuItemTYPE
 * 
 * Class that handles menuitems
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Menus
 */  

class MenuItemTYPE extends Node {
	    
	/**
     * menu item name
     * @var string
     * @access public
     */
    public $name;
	
	/**
     * url to menu item
     * @var string
     * @access public
     */
    public $url;
	
	/**
     * sub menus
     * @var MenuItemsTYPE
     * @access public
     */
    public $submenu;
	
	/**
     * permission required to access menu item
     * @var string
     * @access public
     */
    private $permission;
	
	/**
     * constructor
     * initiates db to the menu collection
     * can instantiate a lable here with $name     
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		parent::__construct( $db, MENU_COLLECTION );
		$this->name = '';
		$this->url = '';
		$this->submenu = new MenuItemsTYPE( $db );
		$this->permission = '';			
	}
	
	/**
     * getPermission
     * get Permission
     * @access public   
     * @return string     
     */    
    public function getPermission(){ return $this->permission; }
	
	/**
     * display
     * display menu items if user has permission to see them
     * can be recursive to keep going down structure
     * can be wrapped in something other then an li
     * @access public
     * @param string|array $active - optional
     * @param string $wrapper - optional
     * @param bool $rec - recursive optional 
     * @param string $class - optional
     * @param int $levels - optional     
     * @return array     
     */    
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
			
			echo '<div class="subMenu hide">';
			$this->submenu->display($active, $wrapper, $rec, $class, $levels );
			echo '</div>';
		}
				
		echo "</$wrapper>";
	}
	
	
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		$bson = array(
			'name'=>$this->name,		
			'url'=> $this->url,	
			'permission'=>$this->permission,
		);
		return parent::prepare() + $bson + $this->submenu->prepare();		
	}
	
	/**
     * read
     * reads from db
     * @access  public
     * @param assocArray $bson
     */
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
	
	
    /**
     * formatUrl
     * formats a url, returns hash for links that will be dropdowns
     * @access  public
     * @param string $url
     */  
    static public function formatUrl( $url ){
	 	if ( $url && is_string($url)){
	 		return $url;
	 	}
		else
			return '#';
	 }

}

?>
	