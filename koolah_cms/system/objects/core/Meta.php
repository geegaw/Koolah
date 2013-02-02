<?php
	
class MetaTYPE{
		
	public $creationData;
	public $modificationHistory;
 	
	public function __construct()
	{
		$this->creationData = new CreationData();
		$this->modificationHistory = new modificationHistory();		
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array(
			'creationData' => $this->creationData->prepare(),
			'modificationHistory' => $this->modificationHistory->prepare() );		
	}
	
	public function read( $bson )
	{
		if ( isset($bson['creationData']) )
			$this->creationData->read( $bson['creationData'] );
		if ( isset($bson['modificationHistory']) )
			$this->modificationHistory->read( $bson['modificationHistory'] );		
	}
}	

class CreationData{
	public $created_at = null;
	public $created_by = null;
	
	public function set()
	{
		$this->created_at = microtime();
		if (!session_id())
            session_start();
		$this->created_by = $_SESSION['user'];		
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		if ( !$this->created_at)
			$this->set();
		return array(
			'created_at' => $this->created_at,
			'created_by' => $this->created_by );		
	} 
	
	public function read( $bson ){
		if ( isset($bson['created_at']) )
			$this->created_at = $bson['created_at'];
		if ( isset($bson['created_by']) )
			$this->created_by = $bson['created_by'];		
	}
}

class modificationHistory{
	public 	$modifications = null;
	
	public function update(){
		$modifications[] = new Modification();
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		$bson = null;
		if ( $this->modifications ){
			foreach ( $this->modifications as $modification )
				$bson[]= $this->modification->prepare();
		}
		return array( 'modifications' => $this->modifications );
	}
	public function read( $bson ){
		//if ( isset($bson['modifications']) )
		//	$this->modifications->read($bson['modifications']);				
	}	
}

class Modification{
	public $modified_at;
	public $modified_by;
	
	public function __construct(){
		$this->modified_at = microtime();
		if (!session_id())
            session_start();
		$this->modified_by = $_SESSION['user'];		
	}
	
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array(
			'modified_at' => $modified_at,
			'modified_by' => $modified_by );		
	} 

	public function read( $bson ){
		if ( isset($bson['modified_at']) )
			$this->modified_at = $bson['modified_at'];
		if ( isset($bson['modified_by']) )
			$this->modified_by = $bson['modified_by'];		
	}
}


?>