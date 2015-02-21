<?php
/**
 * MenuTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MenuTYPE
 * 
 * Class that handles menus
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Menus
 */
class MenuTYPE extends Node {
	
	/**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * url to menu
     * @var string
     * @access public
     */
    public $url;
    
    /**
     * boolean to decide if click should open in new tab
     * @var bool
     * @access public
     */
    public $newTab;
    
    /**
     * list of menu items in menu
     * @var MenuItemsTYPE
     * @access public
     */
    public $menuItems;
	
	/**
     * order item goes in
     * @var order
     * @access public
     */
    public $order;
    
    /**
     * array of children inside of menu
     * @var array 
     * @access public
     */
    private $children;
    
    /**
     * id of parent menu
     * @var string
     * @access private
     */
    private $parentID;
    
	/**
     * constructor
     * initiates db to the menu collection
     * can instantiate a lable here with $name     
     * @param customMongo $db
     * @param string $name     
     */    
    public function __construct( $db=null, $name=''){
		parent::__construct( $db, MENU_COLLECTION );
        $this->label = new LabelTYPE($db, MENU_COLLECTION  );
		$this->label->label = $name;
        $this->url = null;
        $this->newTab = false;
		$this->order = 0;
       
        $this->children = null;
        $this->parentID = null;
         	
		$this->menuItems = new MenuItemsTYPE($db);
        
	}
    
    /**
     * getChildren
     * get Children
     * @access public   
     * @return string     
     */    
    public function getChildren(){
    	$children = new MenusTYPE();
		$children->get(array('parentID'=>$this->getID()));
    	return $children; 
	}
    
    /**
     * getParentID
     * get parentID
     * @access public   
     * @return string     
     */    
    public function getParentID(){ return $this->parentID; }
    
    /**
     * setParentID
     * set parentID
     * @access public   
     * @param string $id     
     */    
    public function setParentID( $id ){ $this->parentID = $id; }
    
    /**
     * clear
     * empty children
     * @access public   
     */    
    public function clear(){ $this->children = null; }
	
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
		if ( !is_array( $active ) )	
			$active = array( $active );
		$this->menuItems->display( $active, $wrapper, $rec, $class, $levels );
	}
	
	
	/**
     * isEmpty
     * return true if menuitems is empty
     * @access  public
     * @return bool
     */
    public function isEmpty(){ return $this->menuItems->isEmpty(); }
	
	/**
     * isNotEmpty
     * return true if menuitems is not empty
     * @access  public
     * @return bool
     */
    public function isNotEmpty(){ return $this->menuItems->isNotEmpty(); }
	
	
	
	/**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
	    $bson = array(
	       'url' => $this->url,
	       'newTab' => $this->newTab,
	       'children' => $this->prepareChildren(),
	       'parentID' => $this->parentID,
	       'order' => $this->order,
        );
		return parent::prepare() + $bson + $this->label->prepare() + $this->menuItems->prepare();		
	}
    
    /**
     * prepareChildren
     * prepares children for sending to db
     * only need child id and display label
     * @access  private
     * @return assocArray
     */
    private function prepareChildren(){
        $children = null;    
        if ( $this->children ){
            foreach( $this->children as $child )
                $children[] = array(
                    'id'=>$child->getID(), 
                    'label'=>$child->label->label
                 );
        }
        return $children;
    }
    
	/**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
	    $this->clear();
        parent::read($bson);
        $this->menuItems->read( $bson );
        
        if ( is_array($bson) )
            self::readAssoc($bson);
        elseif( is_object($bson) )
            self::readObj( $bson );
        elseif( is_string($bson) )
            $this->readJSON( $bson );
        else 
            // TODO return error
            return;  
	}
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
		if ( array_key_exists('url', $bson) )
            $this->url = $bson['url'];
        if ( array_key_exists('newTab', $bson) )
            $this->newTab = $bson['newTab'];
		if ( array_key_exists('order', $bson) )
            $this->order = $bson['order'];
        if ( array_key_exists('parentID', $bson) ){
            $this->parentID = $bson['parentID'];
            if ( $this->parentID < 0 )
                $this->parentID = null;
        }
		if ( array_key_exists('label', $bson) )
            $this->label->read( $bson );
        if ( array_key_exists('children', $bson) )
            $this->readChildren($bson['children']);    
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
              $this->url = $obj->url;
              $this->newTab = $obj->newTab;
			  $this->order = $obj->order;
              $this->parentID = $obj->parentID;
              $this->label->label = $obj->label;
              $this->readChildren($obj->children);
        }    
    }
    
    /**
     * readChildren
     * reads from db
     * fetches menuitems from db to fill in data
     * @access  public
     * @param assocArray|object|string $children
     */
    private function readChildren( $children ){
        if (is_array($children)){
	        foreach ( $children as $child ){
	            $menuItem = new MenuTYPE();
	            if (is_array($child)  )
	                $menuItem->getByID($child['id']);
	            elseif( is_object($child) )
	                $menuItem->getByID($child->id);
	            else
	                $menuItem->getByID($child);
	            $this->children[] = $menuItem;
	        }
		}
    }
    
   /**
     * del
     * deletes self from db and children
     * @access  public
     * @return StatusTYPE
     */
     public function del(){
        $status = $this->delChildren();    
        if ( $status->success() )
            $status = parent::del();
        return $status;
    }
    
    /**
     * del
     * deletes children from db
     * @access  private
     * @return StatusTYPE
     */
     private function delChildren(){
        $status = new StatusTYPE();
		$children = $this->getChildren();
        if ( $children->length() ){
            foreach( $children->menus() as $child ){
                $status = $child->del();
                if ( !$status->success() )
                    return $status;
            }
        }
        return $status;
    }
}
?>
	