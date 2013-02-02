<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    
    include_once( '../elements/objects/types/FileTYPE.php' );    
    $file = new FileTYPE($cmsDB);
    
    $pathToUploads = '../uploads/';
    $pathToTemp = '../tmp';
    
    if ( isset( $_POST['fileID']) && isset($_POST['fileExt']) && isset($_FILES['file']) )
    {
        $file->getByID ($_POST['fileID'] );
        $filename = $_POST['fileID'].'.'.$_POST['fileExt'];  
        $newFile = $pathToUploads.$filename;
        $file->setFilename($filename);
        move_uploaded_file($_FILES["file"]["tmp_name"],$newFile);
        if ( $status = $file->save() )
            echo json_encode( array('status'=>'success') );              
        else
            echo json_encode( array('status'=>'an error occrued while saving') );
    }    
    else
        echo json_encode( array('status'=>'an error occrued while saving') );
?>
