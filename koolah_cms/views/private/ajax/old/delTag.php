<?php
    
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/TagTYPE.php' );
    
    
    $tag = new TagTYPE($cmsDB);
    
    $msg = 'an error occrued while deleting'; 
    if ( isset( $_POST['id']))
    {
        $tag->getByID( $_POST['id'] );
        $status = $tag->delete(); 
        if ($status)
            $msg = 'success';     
    }
    echo json_encode( array('status'=>$msg) );
?>
