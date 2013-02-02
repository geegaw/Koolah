<?php
    
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/UserTYPE.php' );
    
    
    $user = new UserTYPE($cmsDB);
    
    if ( isset( $_POST['id']))
    {
        $user->setUserID( $_POST['id'] );
        $status = $user->reinstate(); 
        if ($status)
            echo json_encode( array('status'=>'success') );     
        else
            echo json_encode( array('status'=>'an error occrued while reinstating') );        
    }
    else
        echo json_encode( array('status'=>'an error occrued while reinstating') );
?>
