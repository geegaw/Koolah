<?php
/**
 * UserHistoryTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * UserHistoryTYPE
 * 
 * Extends Nodes to work with PageVisitTYPE
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\UserHistory
 */
class UserHistoryTYPE extends Nodes{
        
    /**
     * constructor
     * initiates db to the user history collection     
     * @param customMongo $db
     * @param string $collection     
     */    
    public function __construct( $db=null, $collection = USER_HISTORY_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    /**
     * pageVisits
     * get user page visits and filter by distinct
     * @access public          
     * @param int $length
     * @param int $offset
     * @param bool $distinct
     * @return array        
     */    
    public function pageVisits($length=null, $offset=0, $distinct=false){
         $els = $this->nodes;    
         if ( $distinct ){
             $distinct = null;
             foreach( $this->nodes as $el ){
                 $distinct[ $el->url ] = $el;
                 if ( $length && count($distinct) >= $length )
                    break;
             }
             $els = array_values($distinct);
         }
         if ( $length )
             $els = array_slice($els, $offset, $length);
         return $els; 
    }
    
    /**
     * lastPageVisit
     * get most recent page visited
     * @access public          
     * @return PageVisitTYPE|NULL        
     */    
    public function lastPageVisit(){
        if ( $this->nodes && is_array($this->nodes)){
            return $this->nodes[ count($this->nodes)-1 ];
        }
        return null;
    }
    
    /**
     * update
     * update user's most recent
     * page visit, save if desired
     * @access public          
     * @param string $userID
     * @param string $title
     * @param string $url
     * @param bool $save
     * @return StatusTYPE        
     */    
    public function update( $userID, $title, $url, $save=true ){
        $status = new StatusTYPE();
        
        $lastPage = $this->lastPageVisit();
        if ( $lastPage && $lastPage->url == $url )
            return $status;  
            
        $pageVisit = new PageVisitTYPE($userID, $this->db);
        $pageVisit->update($title, $url);    
        if ($save)
            $status = $pageVisit->save();
        return $status;
    }
    
    /**
     * updateAction
     * update user's most recent
     * action, save if desired
     * @access public          
     * @param string $action
     * @param string $classname
     * @param string $id
     * @param bool $save
     * @return StatusTYPE        
     */    
    public function updateAction( $action, $classname, $id, $save=true   ){
        $status = new StatusTYPE();
        $latestVisit = $this->pageVisits(1);
        if ($latestVisit && isset($latestVisit[0])){
            $latestVisit = $latestVisit[0];
            $latestVisit->updateAction( $action, $classname, $id );
            if ($save)
                $status = $latestVisit->save();
        }
        return $status;
    }
    
    /**
     * getByUserID
     * get all user visits
     * order by time visted
     * @access public          
     * @param string $userID
     * @param bool $distinct
     */    
    public function getByUserID( $userID, $distinct=null ){
        $this->get( array( 'userID'=>$userID ), null, array('timestamp'=>-1), $distinct );
    }
    
    /**
     * get
     * gets from parent and reads response
     * @access public          
     * @param assocArray $q -- query
     * @param array $fields
     * @param array $orderBy -- defaul by timestamp asc
     * @param bool $distinct        
     */    
    public function get( $q=null, $fields=null, $orderBy=array('timestamp'=>-1), $distinct=null ){
        $bsonArray = parent::get( $q, $fields , $orderBy, $distinct);
        if ( count($bsonArray) ){
            foreach ( $bsonArray as $bson ){
                $pageVisit = new PageVisitTYPE();
                $pageVisit->read( $bson );
                $this->append( $pageVisit );
            }
        }   
    }
    
    /**
     * save
     * save user history but cap at 
     * conf defined limit
     * @access public          
     * @param assocArray $bson
     * @return StatusTYPE        
     */    
    public function save($bson=null){
        $this->nodes = array_slice($this->nodes, (MAX_PAGE_HISTORY * -1) );
        return parent::save($bson);   
    }
}
