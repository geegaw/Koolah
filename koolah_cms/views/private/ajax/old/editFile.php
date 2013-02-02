<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    
    include_once( '../elements/objects/types/FileTYPE.php' );    
    
    $status = 'an error occrued while saving'; 
    if ( isset( $_POST['id']))
    {
        $file = new FileTYPE($cmsDB);
        $file->getByID ($_POST['id'] );
    
        if ( isset( $_POST['name']))
            $file->name = $_POST['name'];
        
        if ( isset( $_POST['alt']))
            $file->alt = $_POST['alt'];
        
        if ( isset( $_POST['tagId']) && isset( $_POST['tagAction']))
        {
               $tagID = $_POST['tagId'];
               $action = $_POST['tagAction']; 
               if (  $action == 'delete' )
                  $file->delTag( $tagID );
               elseif(  $action == 'add' ) 
                  $file->addTag( $tagID );               
        }                        
        
        if ( $file->save() )
             $status = 'success';
    }
    else
      $status = 'not enough data passed';
    echo json_encode( array('status'=> $status) );
?>
