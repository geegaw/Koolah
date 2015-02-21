<?php
/**
 * FileTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * FileTYPE
 * 
 * class to deal files that are uploaded
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Uploads
 */
class FileTYPE extends Node{
        
    /**
     * label
     * @var LabelTYPE
     * @access public
     */
    public $label;
    
    /**
     * alt term for an image
     * @var string
     * @access public
     */
    public $alt = '';  
    
    /**
     * file description
     * @var string
     * @access public
     */
    public $description = '';
	
	/**
     * credits
     * @var string
     * @access public
     */
    public $credits = '';  
    
    /**
     * taxonomy associated with file
     * @var TaxonomyTYPE
     * @access public
     */
    public $taxonomy;
    
    /**
     * filename of orig file
     * @var string
     * @access private
     */
    private $filename = '';
	
	/**
     * file in base64
     * @var string
     * @access private
     */
    private $file = null;
	
	/**
     * size of file
     * @var int
     * @access public
     */
    public $size = 0;
	
	/**
     * ext
     * @var string
     * @access public
     */
    public $ext = '';
    
    /**
     * list of crops
     * @var ImagesTYPE
     * @access private
     */
    private $crops = null;
    
    /**
     * constructor
     * initiates db to the uploads collection
     * @param customMongo $db
     */    
    public function __construct( $db=null ){
        parent::__construct( $db, UPLOADS_COLLECTION );
        $this->label = new LabelTYPE( $db, UPLOADS_COLLECTION );
        $this->label->label = 'New File';
        $this->taxonomy = new TaxonomyTYPE($db);
		$this->crops = new CropsTYPE();
    }
    
    /**
     * getCrops
     * get crops from the crops collection
     * @access public   
     * @return string     
     */    
    public function getCrops(){
        return $this->crops->crops();   
    }
    
    /**
     * save
     * 
     * @access public   
     * @param assocArray $bson
     * @return StatusTYPE     
     */    
    public function save($bson=null){
        $bson = $this->prepare(true);
        $status = parent::save($bson);
		if ($status->success() && $this->file){
			if (!$this->getFull_Filename()){	
				$this->setFull_Filename();
				$status = $this->save();
			}
			if ($status->success())
				$status = $this->upload();
		}
		if ($status->success() && $this->crops->length())
			$status = $this->crops->save($this->id, $this->getFull_Filename(), $this->ext);
		return $status;
    }
    
    /**
     * isImage
     * checks to see if the file is an image, or can check if from an extension
     * @access public   
     * @param string $ext
     * @return bool     
     */    
    public function isImage($ext=null){
        global $VALID_IMAGES;
        if (!$ext)
            $ext = $this->ext;  
        return (in_Array( $ext, $VALID_IMAGES )); 
    }
   
   /**
     * isDoc
     * checks to see if the file is a document, or can check if from an extension
     * @access public   
     * @param string $ext
     * @return bool     
     */    
     public function isDoc($ext=null){
        global $VALID_DOCS;
        if (!$ext)
            $ext = $this->ext;  
        return (in_array( $ext, $VALID_DOCS )); 
    }
    
    /**
     * isVid
     * checks to see if the file is a video, or can check if from an extension
     * @access public   
     * @param string $ext
     * @return bool     
     */    
     public function isVid($ext=null){
        global $VALID_VIDS;
        if (!$ext)
            $ext = $this->ext;  
        return (in_array( $ext, $VALID_VIDS )); 
    }
    
    /**
     * isVid
     * checks to see if the file is an audio file, or can check if from an extension
     * @access public   
     * @param string $ext
     * @return bool     
     */    
     public function isAudio($ext=null){
        global $VALID_AUDIO;
        if (!$ext)
            $ext = $this->ext;  
        return (in_array( $ext, $VALID_AUDIO )); 
    }
    
    /**
     * isValidType
     * checks to see if the file is valid in koolah, or can check if from an extension
     * @access public   
     * @param string $ext
     * @return bool     
     */    
     public function isValidType( $ext=null ){
        global $VALID_FILES;
        if (!$ext)
            $ext = $this->ext;  
        return (in_array( $ext, $VALID_FILES )); 
    }
    
    /**
     * isValidSize
     * checks to see if the file is in valid size bounds for koolah
     * @access public   
     * @param int $size
     * @return bool     
     */    
    public function isValidSize( $size ){ return $size <=  MAX_FILE_SIZE;}
    
    /**
     * isValid
     * checks to see if the file is in valid for koolah, or can check if from an extension
     * @access public   
     * @param int $size
     * @param string $ext
     * @return bool     
     */    
    public function isValid(  $size, $ext=null ){ return $this->isValidType($ext) && $this->isValidSize($size); }
    
    /**
     * getPublic_Filename
     * get the file name for public purposes
     * @access public   
     * @return string     
     */    
    public function getPublic_Filename(){
        $filename = '';
        if ( $this->filename )
            $filename = str_replace(UPLOADS_DIR, '', $this->filename);
        return $filename;
    }
    
    /**
     * getFull_Filename
     * get the full filename with path
     * @access public   
     * @return string     
     */    
    public function getFull_Filename(){
    	 return $this->filename; 
	}
	
	/**
     * setFull_Filename
     * get the full filename with path
     * @access public   
     * @return string     
     */    
    public function setFull_Filename(){
    	 $this->filename =  $this->setDestination().'/'.$this->id.'.'.$this->ext;; 
	}
    
    /**
     * getPath
     * get the file path
     * @access public   
     * @return string     
     */    
    public function getPath(){
         $ext = $this->getExt();
         $filename = $this->id.'.'.$ext;
         return str_replace( $filename, '', $this->filename ); 
    }
    
    /**
     * prepare
     * prepares for sending to db
     * different information based on whether this is for save
     * @access  public
     * @param bool $forSave
     * @return assocArray
     */
    public function prepare($forSave=false){
        $bson = array( 
           'alt'=>$this->alt,
           'description'=>$this->description,
           'credits'=>$this->credits,
           'taxonomy'=>$this->prepareTaxonomy(),
           'crops'=>$this->crops->prepare(),
           'ext' => $this->ext,
           'size' => $this->size,
         );
         if ( $forSave ){
            if ($this->getFull_Filename())
				$bson['filename'] = $this->getFull_Filename();
         }
		 elseif ($this->isImage()){
         	$bson['crops'] = $this->crops->prepare();
		 }
         return parent::prepare() + $bson + $this->label->prepare();
    }
    
    /**
     * prepareTaxonomy
     * prepage the taxonomy
     * @access  private
     * @return assocArray
     */
    private function prepareTaxonomy(){
        $taxonomy = null;
        if ( $this->taxonomy->terms() ){
            foreach($this->taxonomy->terms() as $term){
                $taxonomy[] = array( 
                	'id'=>$term->getID(), 
                	'label'=>$term->label->label 
				);
            }
        }
        return $taxonomy;
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
        $this->data = null;
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
        $this->label->read($bson);
        if (array_key_exists('alt', $bson))
            $this->alt = $bson['alt'];
		if (array_key_exists('description', $bson))
            $this->description = $bson['description'];
		if (array_key_exists('credits', $bson))
            $this->credits = $bson['credits'];
		if (array_key_exists('file', $bson))
            $this->file = $bson['file'];
		if (array_key_exists('filename', $bson))
            $this->filename = $bson['filename'];
		if (array_key_exists('size', $bson))
            $this->size = $bson['size'];
		if (array_key_exists('ext', $bson))
            $this->ext = $bson['ext'];
		if (array_key_exists('taxonomy', $bson))
        	$this->readTaxonomy($bson['taxonomy']);
		if (array_key_exists('crops', $bson))
        	$this->crops->read($bson);
		if (array_key_exists('file', $bson))
            $this->file = $bson['file'];
		
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
        if ( $obj ){
            $this->label->read($obj->label);    
            $this->alt = $bson->alt;
            $this->description = $bson->description;
			$this->credits = $bson->credits;
			$this->file = $bson->file;
            $this->filename = $bson->filename;
            $this->readTaxonomy($bson->taxonomy);
        }    
    }
    
    /**
     * readTaxonomy
     * reads array of term ids and converts to term
     * @access  private
     * @param array $taxonomy
     */
    private function readTaxonomy( $taxonomy ){
        $this->taxonomy->clear();
        if ($taxonomy){
            foreach( $taxonomy as $term ){
                if (is_object($term))
                    $id = $term->id;
                elseif(is_array($term))
                    $id = $term['id'];
                else 
                    $id = $term;
                if ( $id ){
                    $term = new TermTYPE();
                    $term->getByID($id);
                    $this->taxonomy->append( $term );
                }
            }
        }
    }
	
    
    /**
     * upload
     * uploads the actual file, and stores and renames the file
     * @access  private
     * @param file $file
     * @return StatusTYPE
     */
    private function upload(){
        global $VALID_FILES;
        $status = new StatusTYPE();
		try{
			if ( !$this->getID() || !$this->file || !$this->ext || !$this->size)
				throw new Exception('Missing Param(s)');
            if ( !in_array( $this->ext, $VALID_FILES ) )
				throw new Exception($this->ext.' is not a valid file extension');
			if ( $this->size > MAX_FILE_SIZE )
				throw new Exception('file is too large');
			
			if (!$this->getFull_Filename())
				$this->setFull_Filename();
            $filename = $this->getFull_Filename();
			
			$data = explode('base64', $this->file);
			$data = $data[1];
			$success = file_put_contents($filename, base64_decode($data));
            if ( !$success )
				throw new Exception('could not upload file');
        }
		catch (Exception $e){
			$status->setFalse($e->getMessage());
		}            
        return $status;        
    }
    
    /**
     * getType
     * get file type
     * @access public   
     * @return string     
     */    
    public function getType(){
         if ($this->isImage())
            return 'img';
         if ($this->isDoc())
            return 'doc';
         if ($this->isVid())
            return 'vid';
         if ($this->isAudio())
            return 'audio';
         return ''; 
    }
    
    /**
     * getExt
     * get Ext
     * @access public   
     * @return string     
     */    
    public function getExt(){ return $this->ext; }
    
    /**
     * getExtFromFileName
     * get Ext from a filename
     * @access public   
     * @param string $filename
     * @return string     
     */    
    public function getExtFromFileName( $filename=null ){
        if ( !$filename )
            $filename = $this->filename;
        
        $ext = explode('.', $filename);
        $ext = strtolower( $ext[ (count($ext) -1) ] );
        return $ext;
    }
    
    /**
     * setDestination
     * create destination folders and name structure based
     * on date created
     * @access private   
     * @param bool $force
     * @return string     
     */    
    private function setDestination( $force=false ){
        if ( !$this->id  )
        	throw new Exception('file does not exist');
		
        $path = UPLOADS_DIR;
        
        $structure = str_split(UPLOAD_FOLDER_STRUCTURE);
        foreach( $structure as $unit ){
            $path.= '/'.date($unit);
            if (!cmsToolKit::checkDir( $path )) 
                return null;
        }
        
        $path.= '/'.$this->id;
        if (cmsToolKit::checkDir( $path ))
            return $path;
    }
	
	/**
     * setCrops
     * create destination folders and name structure based
     * on date created
     * @access public   
     * @param multi CropsTYPE, null
     */    
    public function setCrops( $crops ){
        if ($crops instanceof CropsTYPE)
			$this->crops = $crops;
		else 
			$this->crops->clear();
    }
	    
    /**
     * del
     * delete file from db, remove all files from file system
     * @access public   
     * @return StatusTYPE     
     */    
    public function del(){
        $status = new StatusTYPE();
		$path = str_replace($this->id.'.'.$this->ext, '', $this->getFull_Filename());
      	if ( cmsToolKit::rmDirDashR( $path ) )
            $status =  parent::del();
        else
            $status->setFalse('could not delete folder');    
        
        return $status;
    }
    
}