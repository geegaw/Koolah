<?php
/**
 * CropTYPE
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * CropTYPE
 * 
 * class to deal with a crop and all of its data
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\types\Uploads
 */
class  CropTYPE{
        
    /**
     * x 
     * @var float
     * @access public
     */
    public $x;
	
	/**
     * x2 
     * @var float
     * @access public
     */
    public $x2;
	
	/**
     * y 
     * @var float
     * @access public
     */
    public $y;
	
	/**
     * y2 
     * @var float
     * @access public
     */
    public $y2;
    
    /**
     * width
     * @var int
     * @access public
     */
    public $w = 0;
    
    /**
     * height
     * @var int
     * @access public
     */
    public $h = 0;
	
	/**
     * ratio
     * @var mongoId
     * @access public
     */
    public $ratio = null;
    
	/**
     * isNew
     * @var bool
     * @access private
     */
    private $isNew = false;
	
	/**
     * setNew
     * set isNew = true
     * @access public   
     */    
    public function setNew(){ $this->isNew = true; }
	
    /**
     * equals
     * check a suspect is the same as this
     * @access public   
     * @param CropTYPE $suspect
     * @return bool     
     */    
    public function equals( $suspect ){
    	if ( !$suspect instanceof CropTYPE)
			return false;
        
        $checkFields = array('ratio', 'w', 'h', 'x', 'x2', 'y', 'y2');
		foreach ($checkFields as $check){
			if ($suspect->$check != $this->$check)
				return false;
		}
		return true;
    }
    
    /**
     * prepare
     * prepares for sending to db
     * @access  public
     * @return assocArray
     */
    public function prepare(){
         $bson = array(
         	'ratio' => $this->ratio,
            'w' => $this->w,
            'h' => $this->h,
            'x' => $this->x,
            'x2' => $this->x2,
            'y' => $this->y,
            'y2' => $this->y2,
         );
         return $bson;
    }
    
    /**
     * read
     * reads from db - clears and handles children's reading
     * calls appropriate method based on $bson type
     * @access  public
     * @param assocArray|object|string $bson
     */
    public function read( $bson ){
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
    	if (array_key_exists('ratio', $bson))
            $this->ratio = $bson['ratio'];
        
        if (array_key_exists('w', $bson))
            $this->w = $bson['w'];
        
        if (array_key_exists('h', $bson))
            $this->h = $bson['h'];
		
		if (array_key_exists('x', $bson))
            $this->x = $bson['x'];
		
		if (array_key_exists('x2', $bson))
            $this->x2 = $bson['x2'];
		
		if (array_key_exists('y', $bson))
            $this->y = $bson['y'];
		
		if (array_key_exists('y2', $bson))
            $this->y2 = $bson['y2'];
    }
    
    /**
     * readObj
     * converts object into Node
     * @access  public
     * @param object $obj
     */
    public function readObj( $obj ){
    	if ( $obj ){
            $this->ratio = $obj->ratio;
            $this->w = $obj->w;
            $this->h = $obj->h;
			$this->x = $obj->x;
            $this->x2 = $obj->x2;
			$this->y = $obj->y;
            $this->y2 = $obj->y2;
        }    
    }
	
	/**
     * save
     * saves all of the crops if need be
     * @access public
	 * @return StatusTYPE          
     */    
    public function save($fileId, $file, $ext){
    	$status = new StatusTYPE();
		if ($this->isNew){
			$coords = $this->getCoords();
			$ratio = new RatioTYPE();

			if (is_array($this->ratio)){
				$crop = new FileTYPE();
				$crop->getByID($fileId);
				$crop->setID(null);
				$crop->setCrops( new CropsTYPE() );
				$crop->label = new LabelTYPE($crop->getDB(), $crop->getCollection(), $this->ratio['cropName']);
				$status = $crop->save();
				if (!$status->success())
				 	return $status;
			 	$crop->setFull_Filename();
			 	$status = $crop->save();

				if (!$status->success())
				 	return $status;

				$filename = $crop->getFull_Filename();
				$status = CropTYPE::crop($file, $filename, $ext, $this->ratio['cropWidth'], $this->ratio['cropHeight'], $coords );
			}
			else{
				$ratio->getByID($this->ratio);
				$path = str_replace($fileId.'.'.$ext, '', $file);
				$path = $path.$ratio->label->getRef();
				cmsToolKit::checkDir( $path );
				foreach ($ratio->sizes->sizes as $size){
					$crop = $path.'/'.$size->label->getRef().'.'.$ext;
					$status = CropTYPE::crop($file, $crop, $ext, $size->w, $size->h, $coords );
					if (!$status->success())
						return $status; 	
				}
			}
		}
		return $status;
	}
	
	private function getCoords(){
		return array(
			'x' => $this->x,
			'x2' => $this->x2,
			'y' => $this->y,
			'y2' => $this->y2,
		);
	}
	
	/**
     * compare
     * checks if nodes match
     * @access public
	 * @param CropTYPE $suspect
	 * @return bool          
     */    
    public function compare($suspect){
		return $suspect->ratio == $this->ratio;
	}
	
	/**
     * copyFile
     * copy a file and rename/move it
     * @access static   
     * @param string $oldFile
     * @param string $newFilename
     * @return StatusTYPE     
     */    
    static public function copyFile($oldFile, $newFilename){
        $status = new StatusTYPE();        
        if ( file_exists($newFilename) ){
            if( !unlink($newFilename) ){
                $status->setFalse( 'could not copy file first time' );
                return $status;
            }
        }
        if ( !copy($oldFile, $newFilename ) )
            $status->setFalse( 'could not copy file' );        
        return $status;
    }
	
	/**
     * crop
     * resize an image based on desired width and height
     * @access private   
     * @param image $image
     * @param int $w
     * @param int $h
     * @return StatusTYPE     
     */    
    static public function crop( $parentImage, $cropImage, $ext, $w, $h, $coords ){
        $status = CropTYPE::copyFile( $parentImage,  $cropImage);        
        
        if ( $status->success() ){
            $new_image = imagecreatetruecolor($w, $h);
			
            imageinterlace($new_image, true);
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
			$color = imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
			imagefill($new_image, 0, 0, $color);
			
            switch( $ext ){
                case 'png':
                    $image_o = imagecreatefrompng($cropImage);
                    $createNew = 'imagepng';
                    break; 
                case 'gif':
                    $image_o = imagecreatefromgif($cropImage);
                    $createNew = 'imagegif';
                    break; 
                default:
                    $image_o = imagecreatefromjpeg($cropImage);
                    $createNew = 'imagejpeg';
                    break;
            }          
            
            try{
            	imagecopyresampled(
                    $new_image, 
                    $image_o, 
                    0,
                    0, 
                    $coords['x'], 
                    $coords['y'], 
                    $w, 
                    $h, 
                    ($coords['x2'] - $coords['x']), 
                    ($coords['y2'] - $coords['y']) 
                );
                $createNew($new_image, $cropImage);
                imagedestroy($new_image);
            }
            catch(exception $e){
                $status->setFalse( 'error cropping image' );
            }
        }
        return $status;
    }
}