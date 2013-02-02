<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/RoleTYPE.php' );
    
    $role = new RoleTYPE($cmsDB);
    
    if ( isset( $_POST['id']))
    {
        $role->setID( $_POST['id'] );
        if ( $role->delete() )
            echo json_encode( array('status'=>'success') );     
        else
            echo json_encode( array('status'=>'an error occrued while deleting') );        
    }
    else
        echo json_encode( array('status'=>'an error occurred while deleting') );
?>
