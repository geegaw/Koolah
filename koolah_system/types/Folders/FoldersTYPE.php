<?php
/**
 * FoldersTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FoldersTYPE
 * 
 * Extends Nodes to work with FolderTYPE 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Folders
 */
class FoldersTYPE extends Nodes {
    
    /**
     * constructor
     * initiates db to the folder collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct($db = null, $collection = FOLDER_COLLECTION) {
        parent::__construct($db, $collection);
    }

    /**
     * children
     * shortcut to access parent nodes     
     * @return array     
     */    
    public function children() {
        return $this->nodes;
    }

    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy
     * @param bool $distinct        
     */    
    public function get($q = null, $fields = null, $orderBy = null, $distinct=null ) { $this->read( parent::get($q, $fields) ); }

    /**
     * prepare
     * prepares for sending to db if child is PageTYPE only index partial data
     * @access  public
     * @return assocArray
     */
    public function prepare() {
        $bson = array();
        if ($this->length()) {
            foreach ($this->children() as $child) {

                if ($child instanceof FolderTYPE) {
                    $bson[] = $child->prepare();
                } 
                elseif ($child instanceof PageTYPE) {
                    $bson[] = array(
                        'type'=> 'page',
                        'id' => $child->getID(),
                    );
                }
            }
        }
        return $bson;
    }
    
    /**
     * read
     * reads from db - clears object ahead of time
     * instantiates each node as proper object     
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
        parent::read($bson);    
        $this->clear();
        if ( $bson ){
            foreach ($bson as $el) {
                if ( is_array( $el ) && isset($el['type']) )
                    $type = $el['type'];
                elseif( is_object($el) )
                    $type = $el->type;        
                if ($type) {
                    if ($type == 'folder') {
                        $child = new FolderTYPE();
                        $child->read($el);
                    } 
                    elseif ($type == 'page') {
                        $child = new PageTYPE();
                        if ( is_array( $el ) && isset($el['id']) )
                            $id = $el['id'];
                        elseif( is_object($el) )
                            $id = $el->id;
                        if ( $id )
                            $child->getOne($id);
                    } 
                    else {
                        $child = new FolderTYPE();
                        $child->label->label = 'unknown';
                    }
                    $this->append($child);
                }
            }
        }
    }
}