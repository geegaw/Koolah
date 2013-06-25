<?php
/**
 * FolderTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FolderTYPE
 * 
 * Class that handles folders that allow for desktop like management
 * of pages  
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Folders
 */
class FolderTYPE extends Node{

	/**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
	
    /**
     * array of children folders and pages
     * @var array
     * @access public
     */
    public $children;
    
    /**
     * id ref to parent folder
     * @var string - id of parent folder
     * @access private
     */
    private $parentID;
    
    /**
     * constructor
     * initiates db to the folder collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection=FOLDER_COLLECTION ){
		parent::__construct( $db, $collection );
		
		$this->label = new LabelTYPE( $db, $collection );
		$this->children = array();
        $this->parentID = null;
	}
	
    /**
     * getParentID
     * get ParentID
     * @access  public
     * @return string
     */
    public function getParentID(){ return $this->parentID; }
    
    /**
     * setParentID
     * set ParentID
     * @access  public
     * @param string  $id
     */
    public function setParentID( $id ){ $this->parentID = $id; }
    
    /**
     * save
     * save folder, set label ref if new folder
     * @access  public
     * @param assocArray $bson
     * @return StatusTYPE     
     */
    public function save($bson=null ){
		if ( !$this->id && !$this->label->getRef())	
			$this->label->setRef();

		return parent::save($bson);
	}
	
    /**
     * del
     * del folder and deletes all children
     * @access  public
     * @return StatusTYPE
     */
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
    
    /**
     * getRoot
     * get Root Folder
     * @access  public
     * @return FolderTYPE
     */
    public function getRoot() {
        $q = array( 'ref'=>FOLDER_COLLECTION_ROOT );        
        $root = new FolderTYPE();
        $root->get($q);
        return $root;
    }
    
    /**
     * initRoot
     * sets up initial root folder structure
     * structure located in SETUP/folders.php     
     * divided amongst pages and widgets and saves to db      
     * @access  public
     * @return StatusTYPE
     */
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
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare( $clean=false ){
	    $bson = array( 
           'children'  => $this->prepareChildren(),
           'parentID'  => $this->parentID,
         );
         return parent::prepare( $clean ) + $this->label->prepare( $clean ) + $bson;		
    }
    
    /**
     * prepareChildren
     * prepares children for sending to db
     * children get only basic information saved to folder     
     * @access  public
     * @return assocArray
     */
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
    
	/**
     * read
     * reads from db
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
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
    
    /**
     * readAssoc
     * converts assocArray into Node
     * @access  public
     * @param assocArray $bson
     */
    public function readAssoc( $bson ){
        if ( isset( $bson['parentID'] ) )
          $this->parentID = $bson['parentID'];
        if ( isset( $bson['label'] ) )
          $this->label->read( $bson );
        if ( isset( $bson['children'] ) )
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
            if ( $obj->parentID ) 
              $this->parentID = $obj->parentID;
            if ( $obj->label ) 
              $this->label->label = $obj->label;
            if ( $obj->children ) 
              $this->readChildren($obj->children);
        }    
    }
    
    /**
     * readChildren
     * reads children and retrieves all of their information
     * from db     
     * @access  public
     * @param assocArray $children
     */
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