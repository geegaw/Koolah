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
     * alt tag for an image
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
     * tags associated with file
     * @var TagsTYPE
     * @access public
     */
    public $tags;
    
    /**
     * template type
     * @var string
     * @access private
     */
    private $templateType;
    
    /**
     * filename of orig file
     * @var string
     * @access private
     */
    private $filename = '';
    
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
        $this->tags = new TagsTYPE($db);
    }
    
    /**
     * getCrops
     * get crops from the crops collection
     * @access public   
     * @return string     
     */    
    public function getCrops(){
        $this->crops = new ImagesTYPE( $this->db );
        $this->crops->get( array('file'=>$this->getID() ));        
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
        return parent::save($bson);
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
            $ext = $this->getExtFromFileName();  
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
            $ext = $this->getExtFromFileName();  
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
            $ext = $this->getExtFromFileName();  
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
            $ext = $this->getExtFromFileName();  
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
            $ext = $this->getExtFromFileName();  
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
    public function getFull_Filename(){ return $this->filename; }
    
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
           'tags'=>$this->prepareTags(),
         );
         if ( $forSave )
            $bson['filename'] = $this->filename;
         else{ 
             $bson['filename'] = $this->getPublic_Filename();
             if ( $this->isImage() ){
                 $this->getCrops();
                 $bson['crops'] = $this->prepareCrops();
             }
         }
         return parent::prepare() + $bson + $this->label->prepare();
    }
    
    /**
     * prepareTags
     * prepage the tags
     * @access  private
     * @return assocArray
     */
    private function prepareTags(){
        $tags = null;
        if ( $this->tags->tags() ){
            foreach($this->tags->tags() as $tag){
                $tags[] = array( 'id'=>$tag->getID(), 'label'=>$tag->label->label );
            }
        }
        return $tags;
    }
    
    /**
     * prepareCrops
     * prepage the crops
     * @access  private
     * @return assocArray
     */
    private function prepareCrops(){
        $crops = null;
        if ( $this->crops->images() ){
            foreach($this->crops->images() as $crop){
                if ( $crop->ratio->getID() )
                    $label = $crop->ratio->label->label;
                else
                    $label = $crop->crop->w.' x '.$crop->crop->h;
                $bson = $crop->prepare();
                $bson['label'] = $label;
                $crops[] = $bson;
            }
        }
        return $crops;
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
        if (isset($bson['alt']))
            $this->alt = $bson['alt'];
        if (isset($bson['description']))
            $this->description = $bson['description'];
        if (isset($bson['filename']))
            $this->filename = $bson['filename'];
        $this->readTags($bson['tags']);
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
            $this->filename = $bson->filename;
            $this->readTags($bson->tags);
        }    
    }
    
    /**
     * readTags
     * reads array of tag ids and converts to tag
     * @access  private
     * @param array $tags
     */
    private function readTags( $tags ){
        $this->tags->clear();
        if ($tags){
            foreach( $tags as $tag ){
                if (is_object($tag))
                    $id = $tag->id;
                elseif(is_array($tag))
                    $id = $tag['id'];
                else 
                    $id = $tag;
                if ( $id ){
                    $tag = new TagTYPE();
                    $tag->getByID($id);
                    $this->tags->append( $tag );
                }
            }
        }
    }
    
    /**
     * upload
     * uploads the actual file, and stores and renames the file
     * @access  public
     * @param file $file
     * @return StatusTYPE
     */
    public function upload( $file ){
        global $VALID_FILES;
        $status = new StatusTYPE();
        if ( $this->getID()  && $file ){
            if ( $ext = $this->getExtFromFileName($file['name']) ){
                if ( in_array( $ext, $VALID_FILES ) ){
                    if ( $file["name"] <= MAX_FILE_SIZE ){
                        $this->filename = $this->setDestination().'/'.$this->id.'.'.$ext;
                        if ( @move_uploaded_file($file['tmp_name'], $this->filename) )
                            $status = $this->save();
                        else
                            $status->setFalse('could not  move file');
                    }
                    else
                        $status->setFalse = 'file is too large';
                }
                else
                    $status->setFalse = 'not a valid file extension';
            }
            else
                $status->setFalse = 'could not get extension';
        }
        else
            $status->setFalse('Missing File or  ID');            
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
    public function getExt(){ return $this->getExtFromFileName(); }
    
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
        if ( (!$this->filename || !$force) && $this->id  ){
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
        return null; 
    }
    
    /**
     * del
     * delete file from db, remove all files from file system
     * @access public   
     * @return StatusTYPE     
     */    
    public function del(){
        $status = new StatusTYPE();
        if ( $this->isImage() ){
            $this->getCrops();
            foreach( $this->crops->images() as $crop ){
                $status = $crop->del();
                if ( !$status->success() )
                    return $status;
            }
        }

        if ( unlink( $this->filename ) ){
            if ( rmdir( $this->getPath() ) )
                $status =  parent::del();
            else
                $status->setFalse('could not delete folder');    
        }
        else
            $status->setFalse('could not delete file');
        
        return $status;
    }
    
}