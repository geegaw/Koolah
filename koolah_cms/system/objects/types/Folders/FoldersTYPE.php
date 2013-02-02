<?php
class FoldersTYPE extends Nodes {
    //CONSTRUCT
    public function __construct($db = null, $collection = FOLDER_COLLECTION) {
        parent::__construct($db, $collection);
    }

    //GETTERS
    public function children() {
        return $this->nodes;
    }

    //GETTERS
    public function get($q = null, $fields = null, $orderBy = null, $distinct=null ) { $this->read( parent::get($q, $fields) ); }

    /***
     * MONGO FUNCTIONS
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
    
    public function read( $bson ){
//debug::printr($bson, true);        
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
    

    /***/

}
