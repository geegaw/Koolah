<?php
/**
 * Loader
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * Loader
 * 
 * load files into scope
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system
 */
class Loader{
    
    /**
     * loadDir
     * load an entire directory
     * keep going down the tree if recursive
     * is set to true
     * @access public
     * @param srting $dir
     * @param bool $r
     */    
    public static function loadDir( $dir, $r=false ){
        if ( is_dir($dir) ){
            $files = glob( $dir."/*.php" );
            foreach( $files as $file ){
                Loader::loadFile( $file );
//                debug::h1($file);
            }
            if ( $r ){
                $dirs = glob( $dir."/*", GLOB_ONLYDIR  );
                foreach( $dirs as $dir )
                    Loader::loadDir( $dir, $r );                
            }
        }
    }
    
    /**
     * loadFile
     * load a file, return if successful
     * @access public
     * @param srting $file
     * @return bool
     */    
    public static function loadFile( $file ){
        if ( file_exists($file) ){
            include( $file );
            return true;
        }
        return false;       
    } 
}
?>