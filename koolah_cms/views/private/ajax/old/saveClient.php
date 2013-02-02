<?php
    
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/ClientTYPE.php' );
    
    if ( isset( $_POST['id']))
        $id = $_POST['id'];
    else
        $id = null;
    
    if ( isset( $_POST['name']))
        $name = $_POST['name'];
    else
    {
        echo json_encode(array('status'=>'A company name must be enteted'));
        return;
    }
    
    if ( isset( $_POST['contact']))
        $contact = $_POST['contact'];
    else
        $contact = null;
    
    $client = new ClientTYPE($cmsDB);
    $client->setCompanyName( $name );
    if ( $contact )
        $client->setCompanyContact( $contact );
    
    
    if ($id)
        $status = $client->update();
    else
        $status = $client->insert();
    
    if ($status)
        echo json_encode( array('status'=>'success') );     
    else        
        echo json_encode( array('status'=>'an error occrued while saving') );
?>
