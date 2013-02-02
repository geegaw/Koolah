<?php
class AliasTYPE extends Node{
    protected $alias;
        
    private $pageID;
    
    public function __construct( $db=null, $alias=null, $pageID=null ){
        parent::__construct( $db, ALIAS_COLLECTION );   
        $this->alias = self::mkSafe( $alias );
        $this->pageID = $pageID;
    }
    
    //GETTERS
    public function getAlias(){ return $this->alias; }
    public function getPageID(){ return $this->pageID; }
    
    //SETTERS
    public function setAlias( $alias ){ $this->alias = self::mkSafe( $alias ); }
    public function setPageID( $pageID ){ $this->pageID = $pageID; }  
    
    public function compare( $suspect ){
        return $suspect == $this->alias; 
    }
    
    public function mkInput(){
        $input = '';    
        return $input;
    }
    
    public function mkCapsule(){
        if ( $this->alias )
            return htmlTools::mkPod( $this->getID(), $this->alias, 'alias'.uniqid(), 'alias', array('editable'=>true) );
        return '';
    }
    
    
    public function save($bson=null){
        if( !$this->exists() )
            return parent::save();
        else{
            $status = new StatusTYPE('alias already exists', false);
            return $status;
        }
    }
    
    //bools
    public function exists( $suspect=null ){
        if (!$suspect)
            $suspect = $this->alias;
        $checker = new AliasTYPE( $this->db );
        $checker->get( array( 'alias'=>$suspect )  );
        return $checker->getID();
    }
    
    /***
     * MONGO FUNCTIONS
     */
    public function prepare(){
        $bson = array( 
           'alias'=>$this->alias, 
           'pageID'=>$this->pageID
         );
        return parent::prepare() + $bson;
    }

    public function read( $bson ){
        parent::read( $bson );    
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
    
    public function readAssoc( $bson ){
//debug::vardump($bson);            
        if ( isset($bson['alias']) )
            $this->alias = $bson['alias'];
        if ( isset($bson['pageID']) )
            $this->pageID = $bson['pageID'];
    }
    
    public function readObj( $obj ){
//debug::vardump($obj);        
        if ( $obj ){
            if (property_exists($obj, 'alias'))
                $this->alias = $obj->alias;        
            if (property_exists($obj, 'pageID'))       
                $this->pageID = $obj->pageID;
        }    
//debug::vardump($this);        
    }
    
    //JSON
    public function toJSON(){
        return parent::toJSON() + json_encode($this);
    }
    
    static public function mkSafe( $str ){
        $str = strtolower($str);
        $str = str_replace( ' ', '_', $str);
        $str = strtolower($str);
        return urlencode($str);
    }
}

?>
