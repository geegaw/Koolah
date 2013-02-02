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
    
    
	
}

?>