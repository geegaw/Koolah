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
    public function __construct( $id=null, $format=null, $db=null ){
        parent::__construct( $db, UPLOADS_COLLECTION );    
        $this->id = $id;
        $this->format = $format;
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
        if ( !$file->getID() )
            $status->setFalse();
        else{
            if ( $this->format ){
                if ( is_array($this->format) ){
                    if ( $file->isImage() ){
                        if ( ImageTYPE::isLandscapre($file->getID()) )
                            $this->format = $this->format['l'];
                        else                        
                            $this->format = $this->format['p'];
                    }
                    else 
                        $status->setFalse();
                }
             
                $parts = explode('-', $this->format);
                if ( !count($parts) == 2 )
                    $status->setFalse();
                else
                    $this->path = $file->getPath().$parts[0].'/'.$parts[1].'.'.$file->getExt();                
            }
            else
                $this->path = $file->getFull_Filename();
            
            if ( !$this->chkFile() )
                $status->setFalse();
            else{
                $this->name = $file->label->getRef();    
                $this->type = $file->getType();
                $this->ext = $file->getExt();
            }
        }   
        
        return $status;               
    } 
    
    /**
     * chkFile
     * check that file actually exisits
     * @access  private
     * @return bool
     */
    private function chkFile(){
        if ( !$this->path || !file_exists($this->path) )
            return false;
        return true;
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