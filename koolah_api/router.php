<?php
class Router{
    static function serveReq( $req=null ){
        global $cmsDB;  
//debug::vardump($req);       
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
    
    private static function loadReq( $alias ){
        if ( !Router::loadAlias( $alias )  )
            return Loader::loadFile( HTTP_ERRORS_PATH."/404.php" );
        return true;
    }
    
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
    
    private static function loadAlias( $alias ){
        $oAlias = new AliasTYPE();
        $oAlias->get( array('alias' => $alias) );
        //debug::vardump($alias);
        //debug::printr($oAlias, 1); 
        if ( !$oAlias->getID() )
            return false;
        $page = new PageTYPE();
        $page->getByID( $oAlias->getPageID() );
        //debug::printr($page, 1); 
        if ( !$page->getID() || !$page->isPublished() )
            return false;
        $file = PAGES_PATH.'/'.$page->getTemplateFile().'.php';
        
        //debug::vardump($file); 
        
        if ( !file_exists( $file  ) )
            return false;
        //debug::vardump($file, 1);        
        /***
         * NOTE: implement desired system here
         */         
        ob_start();
        require($file);
        //$contents = ob_get_contents();
        //ob_end_clean();
        //echo $contents;
        $contents = ob_get_flush(); 
        
        //debug::vardump($file, 1);
        
        return true;       
    } 
    
}
?>