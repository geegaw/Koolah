<?php
/**
 * MetaTYPE, CreationData, modificationHistory, Modification
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * MetaTYPE
 * 
 * common Meta for all objects
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */	
class MetaTYPE{
	
    /**
     * data about creation
     * @var CreationData
     * @access  public
     */	
	public $creationData;
    
    /**
     * modification history
     * @var modificationHistory
     * @access  public
     */
	public $modificationHistory;
 	
    /**
     * constructor
     */
	public function __construct(){
		$this->creationData = new CreationData();
		$this->modificationHistory = new modificationHistory();		
	}
	
	/**
     * prepare
     * prepares for sending to db
     * if no ref it creates one
     * @access  public
     * @return assocArray
     */
	public function prepare(){
		return array(
			'creationData' => $this->creationData->prepare(),
			'modificationHistory' => $this->modificationHistory->prepare() );		
	}
	
    /**
     * read
     * converts assocArray into MetaTYPE
     * @access  public
     * @param assocArray $bson
     */
	public function read( $bson ){
		if ( isset($bson['creationData']) )
			$this->creationData->read( $bson['creationData'] );
		if ( isset($bson['modificationHistory']) )
			$this->modificationHistory->read( $bson['modificationHistory'] );		
	}
}	

/**
 * CreationData
 * 
 * common Creation Data for all objects
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */ 
class CreationData{
    
    /**
     * date created
     * @var date
     * @access  public
     */ 
	public $created_at = null;
    
    /**
     * id ref to who created it
     * @var string - user id
     * @access  public
     */
	public $created_by = null;
	
    
    /**
     * set
     * sets the created at to current timestamp
     * created at to the current user in the session
     * @access  public
     */
    public function set()
	{
		$this->created_at = date(TIMESTAMP_FORMAT);
        $user = new SessionUser();
		$this->created_by = $user->getID();		
	}
	
	/**
     * prepare
     * prepares for sending to db
     * if no ref it creates one
     * @access  public
     * @return assocArray
     */
	public function prepare(){
		if ( !$this->created_at)
			$this->set();
		return array(
			'created_at' => $this->created_at,
			'created_by' => $this->created_by );		
	} 
	
    /**
     * read
     * converts assocArray into CreationData
     * @access  public
     * @param assocArray $bson
     */
	public function read( $bson ){
		if ( isset($bson['created_at']) )
			$this->created_at = $bson['created_at'];
		if ( isset($bson['created_by']) )
			$this->created_by = $bson['created_by'];		
	}
}

/**
 * modificationHistory
 * 
 * common modification History for all objects
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 * @package koolah\system\core
 
 */ 
class modificationHistory{
    /**
     * list of modifcations
     * @var Modification[]
     * @access  public
     */
	public 	$modifications = null;
	
    /**
     * update
     * adds a new Modification object
     * @access  public
     */
	public function update(){
		$modifications[] = new Modification();
	}
	
    /**
     * lastModified
     * returns the last modification record
     * @access  public
     * @return Modification
     */
    public function lastModified(){
        if( $this->modifications ){
            return end( $this->modifications );
        }
    }
    
	/**
     * prepare
     * prepares for sending to db
     * if no ref it creates one
     * @access  public
     * @return assocArray
     */
	public function prepare(){
		$bson = null;
		if ( $this->modifications ){
			foreach ( $this->modifications as $modification ){
			    if ( is_object( $modification ) && method_exists($modification, 'prepare'))
				    $bson[]= $modification->prepare();
                else
                    $bson[] = $modification;
            }
		}
		return array( 'modifications' => $this->modifications );
	}
    
    /**
     * read
     * converts assocArray into modificationHistory
     * @access  public
     * @param assocArray $bson
     */
	public function read( $bson ){
		if ( isset($bson['modifications']) )
            $this->modifications = $bson['modifications'];				
	}	
}

/**
 * Modification
 * 
 * common modification for all objects
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 * @package koolah\system\core
 
 */ 
class Modification{
    /**
     * date modified
     * @var date
     * @access  public
     */
	public $modified_at;
	
    /**
     * id ref to who modified it
     * @var string - user id
     * @access  public
     */
	public $modified_by;
	
     /**
     * constructor
     */
	public function __construct(){
		$this->modified_at = date(TIMESTAMP_FORMAT);
        $user = new SessionUser();
		$this->modified_by = $user->getID();		
	}
	
	
	/**
     * prepare
     * prepares for sending to db
     * if no ref it creates one
     * @access  public
     * @return assocArray
     */
	public function prepare(){
		return array(
			'modified_at' => $modified_at,
			'modified_by' => $modified_by );		
	} 
    
    /**
     * read
     * converts assocArray into Modification
     * @access  public
     * @param assocArray $bson
     */
	public function read( $bson ){
		if ( isset($bson['modified_at']) )
			$this->modified_at = $bson['modified_at'];
		if ( isset($bson['modified_by']) )
			$this->modified_by = $bson['modified_by'];		
	}
}


?>