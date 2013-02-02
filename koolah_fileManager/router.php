<?php
class Router{
    static function serveReq( $req=null ){
        
        $id = cmsToolKit::getParam('id', $req);
        $format = cmsToolKit::getParam('format', $req);
        $download = cmsToolKit::getParam('download', $req);
        
        if ( !isset($req['id']) ){
            Loader::loadFile( VIEWS_PATH."/400.php" );
            exit;
        }    
        
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