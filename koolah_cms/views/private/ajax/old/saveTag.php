<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    
    include_once( '../elements/objects/types/TagTYPE.php' );    
    $tag = new TagTYPE($cmsDB);
    
    if ( isset( $_POST['id']))
        $tag->getByID ($_POST['id'] );
    
    if ( isset( $_POST['name']))
        $tag->name= $_POST['name'];
    
    if ( isset( $_POST['width']))
        $tag->style->width = $_POST['width'];
    
    if ( isset( $_POST['height']))
        $tag->style->height=  $_POST['height'];
                   
    if ( $status = $tag->save() )
        echo json_encode( array('status'=>'success') );
    else
        echo json_encode( array('status'=>'an error occrued while saving') );
?>
