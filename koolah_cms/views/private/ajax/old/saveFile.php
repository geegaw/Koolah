<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    
    include_once( '../elements/objects/types/FileTYPE.php' );    
    $file = new FileTYPE($cmsDB);
    
    if ( isset( $_POST['id']))
        $file->getByID ($_POST['id'] );
    
    if ( isset( $_POST['name']))
        $file->name= $_POST['name'];
    
    if ( isset( $_POST['alt']))
        $file->alt = $_POST['alt'];
    
    $tags = null;
    if ( isset( $_POST['tags']))
    {
        $tags = $_POST['tags'];
        if ( $tags == 'null' )
            $tags = null;
    }                        
    $file->tags->set( $tags );
    
    if ( $status = $file->save() )
        echo json_encode( array('status'=>'success', 'id'=>$file->getID()) );
    else
        echo json_encode( array('status'=>'an error occrued while saving') );
?>
