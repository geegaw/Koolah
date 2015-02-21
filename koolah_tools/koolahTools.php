<?php
/**
 * koolahToolKit
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * koolahToolKit
 * 
 * CMS tools
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\tools
 */
class koolahToolKit{
	
	/**
     * includeJS
     * include js file(s) with optional .min version
     * @access public 
     * @static
     * @param array|string $files
     * @param bool $min
     */    
    public static function includeJS( $files, $min=false ){
		$ext = '.js';
        if ( $min )
            $ext = '.min'.$ext;    
		if (!is_array($files) )
			$files = array($files);
		foreach ( $files as $file ){
		    if ( is_dir(JS_PATH.'/'.$file) )
				koolahToolKit::includeJSdir( $file );
			else
				echo '<script src="/public/js/'.$file.$ext.'"></script>';			
		} 		
	} 

	/**
     * includeJSdir
     * include js directory(s) with optional .min version
     * @access public
     * @param array|string $dirs
     * @param bool $min
     */    
    public static function includeJSdir( $dirs, $min=false ){
		if (!is_array($dirs) )
			$dirs = array($dirs);
		foreach ( $dirs as $dir ){
			$files = koolahToolKit::getFolderFiles(JS_PATH.'/'.$dir, "*.js", $dir);
			if ( $files )
				koolahToolKit::includeJS( $files, $min );		
		} 		
	}
	
	/**
     * includeCSS
     * include css file(s) with optional .min version
     * @access public
     * @param array|string $files
     * @param bool $min
     */    
    public static function includeCSS( $files, $min=false  ){
//		if ( ENV == 'dev' ){
//            $type = 'less';
//            $path = LESS_PATH;
//        }
//        else{
            $type = 'css';
            $path = CSS_PATH;
//        } 
		$ext = ".$type";
        if ( $min || ENV == 'prod')
            $ext = '.min'.$ext;        
		if (!is_array($files) )
			$files = array($files);
		foreach ( $files as $file ){
			if ( is_dir($path.'/'.$file) )			    
				koolahToolKit::includeCSSdir( $file );
			elseif ($type == 'less')				
				echo '<link rel="stylesheet/'.$type.'" type="text/css" href="/public/'.$type.'/'.$file.$ext.'" />';
			else
				echo '<link rel="stylesheet" type="text/css" href="/public/'.$type.'/'.$file.$ext.'" />';
		} 		
	} 

	/**
     * includeCSSdir
     * include css/less directory(s) with optional .min version
     * @access public
     * @param array|string $dirs
     * @param bool $min
     */    
    public static function includeCSSdir( $dirs, $min=false  ){
	    if ( ENV == 'dev' ){
            $type = STYLE_SHEET_TYPE;
            $path = PUBLIC_PATH.'/'.STYLE_SHEET_TYPE;
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
                $files = array( $dir."/index" );
            else
			     $files = koolahToolKit::getFolderFiles($path.'/'.$dir, "*$ext", $dir);
			if ( $files )
				koolahToolKit::includeCSS($files, $min );            							
		} 		
	}
	
	/**
     * getFolderFiles
     * get files in a folder and return with out their extensions
     * this is a helper for the includeJS/CSS functions
     * @access public
     * @param string $folder
     * @param string $params
     * @param string $dir
     * @return array
     */    
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
    
    /**
     * getFolderFolders
     * get folders in a folder
     * this is a helper for the includeJSDir/CSSDir functions
     * @access public
     * @param string $folder
     * @return array
     */    
    public static function getFolderFolders( $folder){
        $folders = null;  
        if ( is_dir( $folder ) ){
            $folders = glob($folder."/", GLOB_ONLYDIR);            
        }
        return $folders;
    }
	
    /**
     * getParam
     * get param from assocArray with optional default value
     * @access public
     * @param string $what
     * @param assocArray $from
     * @param mixed $default
     * @return mixed
     */    
    public static function getParam( $what, $from, $default=null ){
        if ( is_array($from) && isset( $from[$what] ) )
            return $from[$what];
        return $default;
    }
	
    /**
     * displayDate
     * display a date by defined format
     * may add addional format types to extend this function
     * default is M j - g:ia
     * @access public
     * @param date $date
     * @param string $format
     * @return string
     */    
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
    
    /**
     * objToAssoc
     * convert an object into an assocArray
     * @access public
     * @param object $obj
     * @return assocArray
     */    
    public static function objToAssoc( $obj ){
        return json_decode(json_encode($obj), true);
    }
    
    /**
     * getPublicProperties
     * returns public properties of an object
     * @access public
     * @param object $obj
     * @return array
     */    
    public static function getPublicProperties( $obj ){
        return get_object_vars($obj);
    }
     
    /**
     * getPublicPropertiesKeys
     * returns public property keys of an object
     * @access public
     * @param object $obj
     * @return array
     */    
    public static function getPublicPropertiesKeys( $obj ){
        return array_keys(cmsToolKit::getPublicProperties($obj));
    }
}    
?>
