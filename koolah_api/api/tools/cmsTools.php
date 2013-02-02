<?php
class cmsToolKit{
	
	
	public static function includeJS( $files, $min=false ){
		$ext = '.js';
        if ( $min )
            $ext = '.min'.$ext;    
		if (!is_array($files) )
			$files = array($files);
		foreach ( $files as $file ){
		    if ( is_dir(JS_PATH.'/'.$file) )
				cmsToolKit::includeJSdir( $file );
			else
				echo '<script src="/public/js/'.$file.$ext.'"></script>';			
		} 		
	} 

	public static function includeJSdir( $dirs, $min=false ){
		if (!is_array($dirs) )
			$dirs = array($dirs);
		foreach ( $dirs as $dir ){
			$files = cmsToolKit::getFolderFiles(JS_PATH.'/'.$dir, "*.js", $dir);
			if ( $files )
				cmsToolKit::includeJS( $files, $min );		
		} 		
	}
	
	public static function includeCSS( $files, $min=false  ){
		if ( ENV == 'dev' ){
            $type = 'less';
            $path = LESS_PATH;
        }
        else{
            $type = 'css';
            $path = CSS_PATH;
        } 
		$ext = ".$type";
        if ( $min || ENV == 'prod')
            $ext = '.min'.$ext;        
		if (!is_array($files) )
			$files = array($files);
		foreach ( $files as $file ){
			if ( is_dir($path.'/'.$file) )			    
				cmsToolKit::includeCSSdir( $file );
            else				
				echo '<link rel="stylesheet/'.$type.'" type="text/css" href="/public/'.$type.'/'.$file.$ext.'" />';
		} 		
	} 

	public static function includeCSSdir( $dirs, $min=false  ){
	    if ( ENV == 'dev' ){
            $type = 'less';
            $path = LESS_PATH;
        }
        else{
            $type = 'css';
            $path = CSS_PATH;
        } 
        $ext = ".$type";
		if (!is_array($dirs) )
			$dirs = array($dirs);
		foreach ( $dirs as $dir ){
		    if ( ENV == 'dev' )
                $files = array( $dir."/toc" );
            else
			     $files = cmsToolKit::getFolderFiles($path.'/'.$dir, "*$ext", $dir);
			if ( $files )
				cmsToolKit::includeCSS( $files, $min );            							
		} 		
	}
	
	
	public static function getFolderFiles( $folder, $params=null, $dir=null ){
		$files = null;	
		if ( is_dir( $folder ) ){
			if($files = glob($folder."/".$params)){
				$tmp = null;
				foreach( $files as $file ){
					$t = explode( $folder, $file );
					$t = $t[ (count($t)-1) ];
					$t = str_replace('.css', '', $t);
                    $t = str_replace('.less', '', $t);
					$t = str_replace('.js', '', $t);
					$tmp[] = $dir.$t;					
				}
				$files = $tmp;
			}
		}
		return $files;
	}
    public static function getFolderFolders( $folder){
        $folders = null;  
        if ( is_dir( $folder ) ){
            $folders = glob($folder."/", GLOB_ONLYDIR);            
        }
        return $folders;
    }
	
	public static function getParam( $what, $from, $default=null ){
        if ( is_array($from) && isset( $from[$what] ) )
            return $from[$what];
        return $default;
    }
	
    public static function checkDir( $dirname ){
        if ( !is_dir( $dirname ) )
            return mkdir($dirname);
        return true; 
    }
    
    public static function displayDate( $date, $format=null ){
        if (is_float($date))
            $date = date(TIMESTAMP_FORMAT, $date);
        
        $date = implode('', explode( '-', $date ));
        
        $date = new DateTime($date);    
        switch( $format ){
            default:
                $format = 'M j - g:ia';
                if ( date('Y') != $date->format('Y') )
                    $format = 'M j, y - g:ia';
                break;
                
        }    
        return $date->format($format); 
    }
}

?>
