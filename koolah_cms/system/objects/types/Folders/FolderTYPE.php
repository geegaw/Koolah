<?php
class FolderTYPE extends Node{

	public $label;
	public $children;
    private $parentID;
    
    public function __construct( $db=null, $collection=FOLDER_COLLECTION ){
		parent::__construct( $db, $collection );
		
		$this->label = new LabelTYPE( $db, $collection );
		//$this->children = new FoldersTYPE( $db, $collection );
		$this->children = array();
        $this->parentID = null;
	}
	
    public function getParentID(){ return $this->parentID; }
    public function setParentID( $id ){ $this->parentID = $id; }
    
    public function save($bson=null ){
		if ( !$this->id && !$this->label->getRef())	
			$this->label->setRef();
//debug::printr($this->prepare(), true);		
		return parent::save($bson);
	}
	
    public function del(){
        $status = new StatusTYPE();    
        if( $this->children ){
            foreach( $this->chliren as $chlid ){
                $status = $child->del();
                if( !$status->success() )
                    return $status;
            }
        }
        return parent::del();
    }
    
    public function getRoot() {
        $q = array( 'ref'=>FOLDER_COLLECTION_ROOT );        
        $root = new FolderTYPE();
        $root->get($q);
        return $root;
    }
    
    public function initRoot() {
        require (SETUP . '/folders.php');
        self::read($rootFolder);
        
        $status = $this->save();
        if ( !$status->success() )
            return $status;
        
        $pages = new FolderTYPE();
        $pages->read( $pagesFolder );
        $pages->setParentID( $this->getID() );
        $status = $pages->save();
        if( !$status->success() )
            return $status;
        $this->children[] = $pages;
    
        $widgets = new FolderTYPE();
        $widgets->read( $widgetsFolder );
        $widgets->setParentID( $this->getID() );
        $status = $widgets->save();
        if( !$status->success() )
            return $status;
        $this->children[] = $widgets;

        return $this->save();
    }
    
    /***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
	    $bson = array( 
           'children'  => $this->prepareChildren(),
           'parentID'  => $this->parentID,
         );
         return parent::prepare() + $this->label->prepare() + $bson;		
    }
    public function prepareChildren(){
        $children = array();
        if( $this->children){
            foreach ( $this->children as $child ){
                $children[] = array(
                    'className' => get_class( $child ),
                    'id'        => $child->getID(),
                    'label'   => $child->label->label,
                );
            }
        }
        return $children;
    }
    
	
    public function read( $bson ){
//debug::vardump($bson);        
        parent::read($bson);    
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
//debug::printr($bson, true);        
        if ( isset( $bson['parentID'] ) )
          $this->parentID = $bson['parentID'];
        if ( isset( $bson['label'] ) )
          $this->label->read( $bson );
        if ( isset( $bson['children'] ) )
          $this->readChildren($bson['children']);    
          //$this->children->read($bson['children']);
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            if ( $obj->parentID ) 
              $this->parentID = $obj->parentID;
            if ( $obj->label ) 
              $this->label->label = $obj->label;
            if ( $obj->children ) 
              $this->readChildren($obj->children);
        }    
    }
    
    public function readChildren( $children ){
        $this->children = null;
        if ( $children ){
            foreach( $children as $child ){
                if( is_array($child) ){
                    if( isset($child['className']) && isset($child['id']) ){
                        $obj = new $child['className']();
                        if ( method_exists($obj, 'getByID') ){
                            $obj->getByID($child['id']);
                            $this->children[] = $obj; 
                        }
                    }
                }
                elseif(is_object($child)){
                    if( $child->className && $child->id ){
                        $obj = new $child->className();
                        if ( method_exists($obj, 'getByID') ){
                            $obj->getByID($child->id);
                            $this->children[] = $obj; 
                        }
                    }
                }
            }
        }
    }
    
}

?>