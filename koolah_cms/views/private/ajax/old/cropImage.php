<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    
    include_once( '../elements/objects/types/StatusTYPE.php' );
    include_once( '../elements/objects/types/ImageTYPE.php' );
    include_once( '../elements/objects/types/TagTYPE.php' );
    
    $status = new StatusTYPE();
    $img = new ImageTYPE($cmsDB);
    $tag = new TagTYPE($cmsDB);
    
    if ( isset( $_POST['id']))
        $img->fetchByID ($_POST['id'] );
    else
        $status->setFalse('no file id');
    
    if (  isset($_POST['coords']) )
        $coords = $_POST['coords'];
    else
        $status->setFalse('no coords');
    
    if ( isset($_POST['tag']) && ($_POST['tag'] != 'null') )
        $tag->getByID( $_POST['tag'] );
    
    if ( $status->success() )
    {
        $img->setMother( $_POST['id'] );
        $tag_id = null;
        
        $x1 = $coords['x'];
        $y1 = $coords['y'];
        $x2 = $coords['x2'];
        $y2 = $coords['y2'];
        $w  = $coords['w'];
        $h  = $coords['h'];
        
        if ( $tag_id = $tag->getID() )
        {
            $w = $tag->style->width;
            $h = $tag->style->height;            
        }
        $status = $img->crop( $x1, $y1, $x2, $y2, $w, $h, $tag_id );
    }                                                                   
    
    echo json_encode( array('status'=>$status->msg));    
?>
