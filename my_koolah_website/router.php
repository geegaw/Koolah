<?php
/**
 * Router
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * Router handles all page requests
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\website
 */ 
class Router{
        
    /**
     * serveReq
     * receives a request and loads the request
     * @access  public
     * @param assocArray $req
     */    
    static function serveReq( $req=null ){
        global $cmsDB;  
        if ( !isset($req['f'])){
             if (!Router::loadReq( '/' ) )
                Loader::loadFile( PAGES_PATH."/home.php" );
        }
        else{
            list( $action, $req_uri, $params ) = Router::parseReq( $req['f'] );
            if ( $action === 400 )
                Loader::loadFile( HTTP_ERRORS_PATH."/400.php" );
            //TODO 
            elseif( $action==AJAX_CALL )
                Router::loadReq( AJAX_PATH."/$req_uri" );
            else
                Router::loadReq( $req_uri );
        }
    }
    
    /**
     * loadReq
     * laod the requests corresponding file
     * @access  private
     * @param string $alias
     */    
    private static function loadReq( $alias ){
        if ( !Router::loadAlias( $alias )  )
            return Loader::loadFile( HTTP_ERRORS_PATH."/404.php" );
        return true;
    }
    
    /**
     * parseReq
     * parse the incoming request
     * @access  private
     * @param array $req
     * @return array
     */    
    private static function parseReq( $req ){
        $action = null;
        $params = null;
            
        $parts = explode( '/', $req );
        if ( in_array(AJAX_CALL, $parts )){
            $action = AJAX_CALL;
            $parts = explode( AJAX_CALL, $req );
            unset( $parts[0] );     
            $parts = array_merge( $parts );
        }
            
        return array($action, $req, $params);       
    }
    
    /**
     * loadAlias
     * load the corresponding page for the alias
     * 
     * @access  private
     * @param array $alias
     * @return array
     */    
    private static function loadAlias( $alias ){
        $oAlias = new AliasTYPE();
        $oAlias->get( array('alias' => $alias) );
        if ( !$oAlias->getID() )
            return false;
        $page = new API_PageTYPE();
        $page->getByID( $oAlias->getPageID() );
        if ( !$page->getID() || !$page->isPublished() )
            return false;
        $file = PAGES_PATH.'/'.$page->getTemplateFile().'.php';
        
        if ( !file_exists( $file  ) )
            return false;
        /***
         * NOTE: implement desired system here
         */         
        ob_start();
        require($file);
        //$contents = ob_get_contents();
        //ob_end_clean();
        //echo $contents;
        $contents = ob_get_flush(); 
        
        return true;       
    } 
    
}
?>