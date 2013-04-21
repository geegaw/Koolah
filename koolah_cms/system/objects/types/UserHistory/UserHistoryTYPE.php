<?php

class UserHistoryTYPE extends Nodes{
    //CONSTRUCT 
    public function __construct( $db=null, $collection = USER_HISTORY_COLLECTION ){
        parent::__construct( $db, $collection );    
    }
    
    //GETTERS
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
    
    public function lastPageVisit(){
        if ( $this->nodes && is_array($this->nodes)){
            return $this->nodes[ count($this->nodes)-1 ];
        }
        return null;
    }
    
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
    
    //GETTERS
    public function getByUserID( $userID, $distinct=null ){
        $this->get( array( 'userID'=>$userID ), null, array('timestamp'=>-1), $distinct );
    }
    
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
    
    public function save($bson=null){
        $this->nodes = array_slice($this->nodes, (MAX_PAGE_HISTORY * -1) );
        return parent::save($bson);   
    }
}
