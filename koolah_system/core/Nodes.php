<?php
/**
 * NodesTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * NodesTYPE
 * 
 * most objects will extend Nodes
 * Nodes contains most methods necc to interact with db
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
  */ 
class Nodes{
    /**
     * list of nodes
     * @var Node[]
     * @access  protected
     */
    protected $nodes = null;
    
    /**
     * counter
     * @var int
     * @access  protected
     */
    protected $numNodes = 0;    
    
    /**
     * db connector
     * @var customMongo
     * @access  protected
     */
    protected $db;
    
    /**
     * collection name
     * @var string
     * @access  protected
     */
    protected $collection;
    
    /**
     * constructor
     * can customize db but defaults to the config
     * collection defualts to node 
     * @param customMongo $db 
     * @param string $collection
     */    
    public function __construct( $db=null, $collection='node'){
        if ( $db )    
            $this->db = $db;
        else{
            $conf = new config();
            $this->db = $conf->cmsMongo;
        }           
        $this->collection = $collection;                
    }
    
    
    /**
     * length
     * @access  public
     * @return int 
     */
    public function length(){ return $this->numNodes; }
    
    
    /**
     * set
     * takes array and enters them into nodes
     * @access  public
     * @param array $nodes 
     */
    public function set( $nodes ){
        $this->clear();
        if ( count($nodes) ){
            foreach ( $nodes as $nodes )
                $this->append( $nodes );
        }       
    }
    
    /**
     * clear
     * emptys nodes and numNodes values
     * @access  public
     */
    public function clear(){
        $this->nodes = null;
        $this->numNodes = 0;        
    }
    
    /**
     * append
     * adds a node to nodes and increments numNodes
     * @access  public
     * @param Node $node 
     */
    public function append( $node ){
        $this->nodes[] = $node;
        $this->numNodes++;      
    }
    
    /**
     * remove
     * looks for the node passed
     * if found it removes it from nodes and decrements numNodes
     * @access  public
     * @param Node $node 
     * @return StatusTYPE
     */
    public function remove( $node ){
        $status = new StatusTYPE();
        if ( $node ){
            $found = false;                                  
            for ( $i = 0; ($i < $this->numNodes) && !$found; $i++ ){
                if ( $node->getID() == $this->nodes[$i]->getID() ){
                    $this->numNodes--;
                    unset( $this->nodes[$i] );
                    $found = true;                    
                }
            }
        }
        else
            $status->setFalse( 'invalid field passed' );        
        return $status;    
    }
    /***/
    
    /**
     * find
     * looks for a node passed
     * returns all occurrences
     * @access  public
      * @uses Node::compare
     * @param Node $suspect 
     * @return array
     */
    public function find( $suspect ){
        $objs = array();
        if ( $this->nodes ){
            foreach( $this->nodes as $node ){
                if ( method_exists($node, 'compare') && $node->compare($suspect) ){
                    $objs[] = $node;
                }                    
            }
        }   
        return $objs;   
    }
    
    /**
     * get
     * gets from db by query
     * and get fields specified all if left empty
     * and orderby desired
     * and unique if desired
     * @access  public
     * @param string $q
     * @param array|string $fields
     * @param array|string $orderBy
     * @param bool $distinct
     */
    public function get( $q=null, $fields=null, $orderBy=null, $distinct=null ){
        return $this->db->get( $this->collection, $q, $fields, $orderBy, $distinct );       
    }
    
    /**
     * isNotEmpty
     * returns true is nodes contains at least one node
     * @access  public
     * @return bool
     */
    public function isNotEmpty(){ return (bool)$this->numNodes; }
    
    /**
     * isEmpty
     * returns true is nodes contains no nodes
     * @access  public
     * @return bool
     */
    public function isEmpty(){ return !$this->isNotEmpty(); } 
    
     
    /**
     * save
     * saves objects to db
     * @access  public
     * @return StatusTYPE
     */
    public function save(){
        if ( $this->numNodes){
            foreach ( $this->nodes as $node )
                $bson[] = $node->prepare();
        }
        list( $status, $id ) = $this->db->save( $this->collection, $bson );
        return $status; 
    }   
    
    /**
     * del
     * deletes all nodes in nodes from db
     * @access  public
     * @return StatusTYPE
     */
    public function del(){
        $status = new StatusTYPE();
         if ( $this->numNodes){
            foreach ( $this->nodes as $node ){
                if ( method_exists($node, 'del') ){    
                    $delStatus = $node->del();
                    if ( !$delStatus->success() )
                        $status->setFalse( 'errors occurred while deleting' );
                }
            }
         }
         return $status; 
    }
    
    /**
     * deleteCollection
     * deletes entire collection
     * @access  public
     * @return StatusTYPE
     */
    public function deleteCollection(){ return $this->db->del( $this->collection, null, false); }
    /***/
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = null;
        if ( $this->numNodes ){
            foreach ( $this->nodes as $node )
                if( @get_class( $node ) )
                    $bson[] = $node->prepare();
        }
        return $bson;
    }
    
    /**
     * read
     * reads from db
     * clears self and then adds newly read items
     * @access  public
     * @param assocArray $bson
     */
    public function read( $bson ){
        if ( $bson ){
            $this->clear();         
            foreach ( $bson as $node )
                $this->append($node);
        }                       
    }

}

?>