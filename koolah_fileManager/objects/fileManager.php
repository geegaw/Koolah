<?php
/**
 * FileManager
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * FileManager extends nodes
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms
 */ 
class FileManager extends Node{
    
    /**
     * file id
     * @var string
     * @access  public
     */ 
    public $id;
    
    /**
     * format
     * @var string
     * @access  public
     */ 
    public $format;
	
	/**
     * useRetina
     * @var bool
     * @access  public
     */ 
    public $useRetina;
    
    /**
     * path to file
     * @var string
     * @access  public
     */ 
    public $path = '';
    
    /**
     * name
     * @var string
     * @access  public
     */ 
    public $name = '';
    
    /**
     * type
     * @var string
     * @access  public
     */ 
    public $type = '';
    
    /**
     * extention
     * @var string
     * @access  public
     */ 
    public $ext = '';
    
    /**
     * constructor
     * initiates db to the uploads collection     
     * @param string $id - optonal
     * @param string $format - optonal
     * @param customMongo $db - optonal
     */    
    public function __construct( $id=null, $format=null, $useRetina=false, $db=null ){
        parent::__construct( $db, UPLOADS_COLLECTION );    
        $this->id = $id;
        $this->format = $format;
		$this->useRetina = (bool)$useRetina;
    }   
        
    /**
     * load
     * load the image
     * @access  public
     * @return StatusTYPE
     */
    public function load(){
        $status = new StatusTYPE();
            
        $file = new FileTYPE();
        $file->getByID( $this->id );
		
		try{
			if ( !$file->getID() )
				throw new Exception('file object not found');
			if ( !$file->isImage() ) // TODO support more file types
				throw new Exception('invalid file type');
        	
        	if ( $this->format ){
                if ( is_array($this->format) && isset($this->format['l']) && isset($this->format['p'])){
            		if ( ImageTYPE::isLandscape($file->getFull_Filename()) )
                        $this->format = $this->format['l'];
                    else                        
                        $this->format = $this->format['p'];
                }
             	
				if (is_string($this->format)){
					$parts = explode('-', $this->format);
	                if ( !count($parts) == 2 )
	                    $status->setFalse();
	                else{
	                    $this->path = $file->getPath().$parts[0].'/'.$parts[1].'.'.$file->getExt();
						$this->chkFile($parts[0]);
					}
				}
				elseif (isset($this->format['maxW']) && isset($this->format['maxH'])){
					$this->handleCustomSize( $file, $this->format['maxW'], $this->format['maxH'] );
				}             
            }
            else
                $this->path = $file->getFull_Filename();
            
			if ($this->useRetina)
				$this->path = ImageTYPE::getRetinaPath($this->path);

			if ( !$this->chkFile() )
                throw new Exception('file not found');

            $this->name = $file->label->getRef();    
            $this->type = $file->getType();
            $this->ext = $file->getExt();
		}
		catch (exception $e){
			$status->setFalse($e->getMessage());
		}
        return $status;               
    } 
    
    /**
     * chkFile
     * check that file actually exisits
     * @access  private
     * @return bool
     */
    private function chkFile($ratio=null){
        if ( !$this->path || !file_exists($this->path) ){
        	if ($ratio){
				$file = new FileTYPE();
				$file->getByID($this->id);
				$crops = $file->getCrops();
	        	
				foreach($crops->images() as $crop){
					if ( $crop->ratio->label->getRef() == $ratio ){
						$status = $crop->save();
						return $status->success();	
					}	
				}
			}
        	return false;	
        }   
        return true;
    }
	
	/**
     * getRatio
     * get ratio from format
     * @access  private
     * @return bool
     */
    private function getRatio($format){
        $parts = explode('-', $format);
        return $parts[0];
    }
    
	/**
     * handleCustomSize
     * handle scenario of a custom width and height in the format
     * @access  private
	 * @param int $w - width
	 * @param int $h - height
     * @return bool
     */
    private function handleCustomSize($file, $w, $h){
    	list($width, $height, $type, $attr) = getimagesize($file->getFull_Filename());
		
		$newH = (int) ($w * $height / $width);
		$this->path = $file->getPath().'custom/w'.$w.'.'.$file->getExt();
		
		if ($newH > $h){
			$newH = $h;
			$newW = (int) ($width * $h / $height);
			$this->path = $file->getPath().'custom/w'.$newW.'.'.$file->getExt();
		}
		else{
			$newW = $w;	
		}
		
		if (!$this->chkFile()){
			cmsToolKit::checkDir($file->getPath().'custom');
			$newImage = new ImageTYPE();
			$newImage->file = $file;
			$newImage->crop->coords = (object)array('x' => 0, 'y' =>0, 'x2' => $width, 'y2' => $height);	
			$newImage->crop($this->path, $newW, $newH);
		}
		
    } 
	
    /**
     * imageExt
     * get special case for image extentions
     * @access  public
     * @return bool
     */
    public function imageExt(){
        if ( $this->ext == 'jpg' )
            return 'jpeg';
        return $this->ext;
            
    }
}


?>