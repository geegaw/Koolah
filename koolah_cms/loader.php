<?php



class Loader{
	
	public static function loadDir( $dir, $r=false ){
		if ( is_dir($dir) ){
			$files = glob( $dir."/*.php" );
			foreach( $files as $file )
				Loader::loadFile( $file );
			if ( $r ){
				$dirs = glob( $dir."/*", GLOB_ONLYDIR  );
				foreach( $dirs as $dir )
					Loader::loadDir( $dir, $r );				
			}
		}
	}
	
	public static function loadFile( $file ){
	    if ( file_exists($file) ){
			include( $file );
			return true;
		}
		return false;		
	} 
	
	public static function serveReq( $req=null ){
		global $cmsDB;	
//debug::vardump($req);		
		if ( !isset($req['f']) )
			Loader::loadReq( FE_PATH."/home" );
		else{
			list( $action, $req_uri, $params ) = Loader::parseReq( $req['f'] );
			
			if ( $action === 400 )
				Loader::loadFile( HTTP_ERRORS_PATH."/400.php" );
			elseif( $action==AJAX_CALL )
				Loader::loadReq( AJAX_PATH."/$req_uri" );
			elseif( $action==SETUP_CALL )
				Loader::loadReq( SETUP_CALL."/$req_uri" );
			else
				Loader::loadReq( FE_PATH."/$req_uri" );
		}
	}
	
	private static function loadReq( $req_uri ){
		if ( !Loader::loadFile( "$req_uri.php" )  )
			Loader::loadFile( HTTP_ERRORS_PATH."/404.php" );
	}
	
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