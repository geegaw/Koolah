<?php

class MenuTYPE extends Node {
	
	public $label;
    public $url;
    public $newTab;
    
    private $children;
    private $parentID;
    
	public $menuItems;
	
	public function __construct( $db=null, $name=''){
		parent::__construct( $db, MENU_COLLECTION );
        $this->label = new LabelTYPE($db, MENU_COLLECTION  );
		$this->label->label = $name;
        $this->url = null;
        $this->newTab = false;
       
        $this->children = null;
        $this->parentID = null;
         	
		$this->menuItems = new MenuItemsTYPE($db);
        
	}
    
    
    public function getChildren(){ return $this->children; }
    public function getParentID(){ return $this->parentID; }
    
    public function clear(){ $this->children = null; }
	
	public function display( $active='', $wrapper='li', $rec=false, $class='', $levels=-1 ){
		if ( !is_array( $active ) )	
			$active = array( $active );
		$this->menuItems->display( $active, $wrapper, $rec, $class, $levels );
	}
	
	
	/***
	 * BOOLS
	 */	
	public function isEmpty(){ return $this->menuItems->isEmpty(); }
	public function isNotEmpty(){ return $this->menuItems->isNotEmpty(); }
	/***/
	
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
	    $bson = array(
	       'url' => $this->url,
	       'newTab' => $this->newTab,
	       'children' => $this->prepareChildren(),
	       'parentID' => $this->parentID,
        );
		return parent::prepare() + $bson + $this->label->prepare() + $this->menuItems->prepare();		
	}
    
    private function prepareChildren(){
//debug::printr($this);        
        $children = null;    
        if ( $this->children ){
            foreach( $this->children as $child )
                $children[] = array('id'=>$child->getID(), 'label'=>$child->label->label);
        }
        return $children;
    }
    
	
	public function read( $bson ){
	    $this->clear();
        parent::read($bson);
        
        //TODO deprecate
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
    
    public function readAssoc( $bson ){
//debug::printr($bson, 1);        
        
        if ( isset( $bson['url'] ) )
            $this->url = $bson['url'];
        if ( isset( $bson['newTab'] ) )
            $this->newTab = $bson['newTab'];
        if ( isset( $bson['parentID'] ) ){
            $this->parentID = $bson['parentID'];
            if ( $this->parentID < 0 )
                $this->parentID = null;
        }
        if ( isset( $bson['label'] ) )
            $this->label->read( $bson );
        if ( isset( $bson['children'] ) )
            $this->readChildren($bson['children']);    
          //$this->children->read($bson['children']);
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
             //if ( $obj->url )
              $this->url = $obj->url;
            //if ( $obj->newTab )
              $this->newTab = $obj->newTab;
            //if ( $obj->parentID ) 
              $this->parentID = $obj->parentID;
            //if ( $obj->label ) 
              $this->label->label = $obj->label;
            //if ( $obj->children ) 
              $this->readChildren($obj->children);
        }    
    }
    
    private function readChildren( $children ){
        
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
    
	/***/
	
}
/***/

?>
	