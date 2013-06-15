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
 * @package koolah\fileManager
 */ 
class Router{
        
    /**
     * serveReq
     * receives a request and loads the request
     * @access  public
     * @param assocArray $req
     */    
    static function serveReq( $req=null ){
        
        $id = koolahToolKit::getParam('id', $req);
        $format = koolahToolKit::getParam('format', $req);
        $formatP = koolahToolKit::getParam('formatP', $req);
        $formatL = koolahToolKit::getParam('formatL', $req);
        $download = koolahToolKit::getParam('download', $req);
        
        if ( !isset($req['id']) ){
            Loader::loadFile( VIEWS_PATH."/400.php" );
            exit;
        }    
        
        if ( !$format && $formatL && $formatP )
            $format = array( 'l'=>$formatL, 'p'=>$formatP );        
        $fm = new FileManager( $id, $format );
        $status = $fm->load();
        if ( !$status->success() ){
            Loader::loadFile( VIEWS_PATH."/400.php" );
            exit;
        }  

        switch( $fm->type ){
            case 'img':
                include( VIEWS_PATH."/image.php" );
                break;
            case 'doc':
                include(  VIEWS_PATH."/doc.php" );
                break;
            case 'vid':
                include(  VIEWS_PATH."/video.php" );
                break;
            case 'audio':
                include(  VIEWS_PATH."/audio.php" );
                break;
            default:
                Loader::loadFile( VIEWS_PATH."/400.php" );
                exit;
                break;
        }
    }
}
?>