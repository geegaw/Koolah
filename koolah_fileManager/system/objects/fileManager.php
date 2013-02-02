<?php

class FileManager extends Node{
    
    public $id;
    public $format;
    
    public $path = '';
    public $name = '';
    public $type = '';
    public $ext = '';
    
    public function __construct( $id=null, $format=null, $db=null ){
        parent::__construct( $db, UPLOADS_COLLECTION );    
        $this->id = $id;
        $this->format = $format;
    }   
        
    public function load(){
        $status = new StatusTYPE();
            
        $file = new FileTYPE();
        $file->getByID( $this->id );
        if ( !$file->getID() )
            $status->setFalse();
        else{
            if ( $this->format ){
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
    
    private function chkFile(){
        if ( !$this->path || !file_exists($this->path) )
            return false;
        return true;
    }
    
    public function imageExt(){
        if ( $this->ext == 'jpg' )
            return 'jpeg';
        return $this->ext;
            
    }
}


?>