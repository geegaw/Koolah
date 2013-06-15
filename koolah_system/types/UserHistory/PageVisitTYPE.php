<?php
/**
 * PageVisitTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * PageVisitTYPE
 * 
 * information about where a user has been
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\UserHistory
 */
class PageVisitTYPE extends Node{
        
    /**
     * title
     * @var string
     * @access public
     */
    public $title = '';
     
    /**
     * url of page visited
     * @var string
     * @access private
     */
    public $url = '';  
    
    /**
     * time page was visited
     * @var date
     * @access private
     */
    public $timestamp;
    
    /**
     * ref to user
     * @var string
     * @access private
     */
    private $userID;
    
    /**
     * actions user performed on page, such as save
     * @var UserActionsTYPE
     * @access private
     */
    private $actions;
    
    /**
     * constructor
     * initiates db to the user history collection
     * sets the timestamp to NOW
     * @param string $userID
     * @param customMongo $db
     */ 
     public function __construct( $userID=null, $db=null ){
        parent::__construct( $db, USER_HISTORY_COLLECTION );
        
        $this->userID = $userID;    
        $this->timestamp = date(TIMESTAMP_FORMAT);
        $this->actions = new UserActionsTYPE();
    }
    
    
    /**
     * update
     * update title and url
     * @access public   
     * @param string $title
     * @param string $url
     */    
    public function update( $title, $url){
        $this->title = $title;
        $this->url = $url;
    }
    
    /**
     * update
     * update title and url
     * @access public   
     * @param UserActionTYPE $action
     * @param string $classname
     * @param string $id
     */    
    public function updateAction( $action, $classname, $id ){
        $this->actions->update( $action, $classname, $id );
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
        $bson = array( 
           'title'=>$this->title,
           'url'=>$this->url,
           'timestamp'=>$this->timestamp,
           'actions' => $this->actions->prepare(),
           'userID' => $this->userID,
         );
         return parent::prepare() + $bson;
    }
    
    /**
     * read
     * reads from db - clears and handles children's reading
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
        if (isset($bson['title']))
            $this->title = $bson['title'];
        if (isset($bson['url']))
            $this->url = $bson['url'];
        if (isset($bson['timestamp']))
            $this->timestamp = $bson['timestamp'];
        if (isset($bson['actions']))
            $this->actions->read( $bson['actions'] );
        if (isset($bson['userID']))
            $this->userID = $bson['userID'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->title = $obj->title;
            $this->url = $obj->url;
            $this->timestamp = $obj->timestamp;
            $this->actions->read( $obj->actions );
            $this->userID = $obj->userID;
            
        }    
    }  
}