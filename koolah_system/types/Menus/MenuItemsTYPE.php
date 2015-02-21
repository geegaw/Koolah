<?php
/**
 * MenuItemsTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MenuItemsTYPE
 * 
 * Extends Nodes to work with MenuItemTYPE 
 * uses session user to determine permission authorization
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Menus
 */
class MenuItemsTYPE extends Nodes {
		
	/**
     * status
     * @var StatusType
     * @access  public
     */
    public $status;
		
	/**
     * logged in user
     * @var SessionUserTYPE
     * @access  private
     */
    private $sessionUser;
	
	/**
     * constructor
     * initiates db to the menu collection
     * gets the session user     
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
		$this->sessionUser = new SessionUser( $db );
		$this->status = $this->sessionUser->status;
		if ( $this->status->success() )		
			parent::__construct( $db, MENU_COLLECTION );				
	}
	
	/**
     * menuItems
     * shortcut to access parent nodes
     * @access public     
     * @return array     
     */    
    public function menuItems(){ return $this->nodes; }
	
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
		if ( $this->menuItems() ){
			if ( !is_array( $active ) )	
				$active = array( $active );
			foreach ( $this->menuItems() as $menuItem ){
				if ( $menuItem->userHasPermission() )
					$menuItem->display( $active, $wrapper, $rec, $class, $levels );
			}
		}			
	}
	
	
	/**
     * isNotEmpty
     * return true if nodes is not empty
     * @access  public
     * @return bool
     */
    public function isNotEmpty(){return (bool)$this->numNodes; }
	
	/**
     * isEmpty
     * return true if nodes is empty
     * @access  public
     * @return bool
     */
    public function isEmpty(){  return !$this->isNotEmpty(); }
	
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
		return array('menuItems'=>parent::prepare() );		
	}
	
	/**
     * read
     * reads from db
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
		if ( isset($bson['menuItems']) ){
			foreach ( $bson['menuItems'] as $menuItem ){
				$tmp = new MenuItemTYPE( $this->db );
				$tmp->read( $menuItem );				
				$this->append( $tmp );	
			}			
		}					
	}	
}

?>
	