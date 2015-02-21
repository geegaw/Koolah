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
 * @package koolah\cms
 */ 
class Router{
	
	/**
     * serveReq
     * receives a request and loads the request
     * @access  public
     * @param assocArray $req
     */    
    public static function serveReq( $req=null ){
		global $cmsDB;	
		if ( !isset($req['f']) )
			Router::loadReq( FE_PATH."/home" );
		else{
			list( $action, $req_uri, $params ) = Router::parseReq( $req['f'] );
			if ( $action === 400 )
				Loader::loadFile( HTTP_ERRORS_PATH."/400.php" );
			elseif( $action==AJAX_CALL )
				Router::loadReq( AJAX_PATH."/$req_uri" );
			elseif( $action==SETUP_CALL )
				Router::loadReq( SETUP_CALL."/$req_uri" );
			elseif ($req_uri == 'signin')
				Router::loadReq( FE_PATH."/$req_uri" );
			elseif ($req_uri == 'signout')
				Router::loadReq( FE_PATH."/$req_uri" );
			else
				Router::loadReq( FE_PATH."/default" );
		}
	}
	
	/**
     * loadReq
     * laod the requests corresponding file
     * @access  private
     * @param string $req_uri
     */    
    private static function loadReq( $req_uri ){
		if ( !Loader::loadFile( "$req_uri.php" )  )
			Loader::loadFile( HTTP_ERRORS_PATH."/404.php" );
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
		$req_uri = null;
		$params = null;
			
		$parts = explode( '/', $req );
        if ( in_array(AJAX_CALL, $parts )){
			$action = AJAX_CALL;
            unset( $parts[0] ); 	
			$parts = array_merge( $parts );
		}
		elseif( $parts[0] == SETUP_CALL){
			$action = SETUP_CALL;
			unset( $parts[0] ); 	
			$parts = array_merge( $parts );
		}

		$req_uri = $parts[0];
		unset( $parts[0] );
		$params = $parts;
		return array($action, $req_uri, $params);		
	}
	
}

?>