<?php
    session_start();
    require( '../config/config.php' );
    
    include_once('../elements/objects/customMongo.php');
    $cmsMongo = new customMongo('cms');
	$status = $cmsMongo->status;
	
    include_once( '../elements/objects/types/TemplatesTYPE.php' );
    
    $templates = new TemplatesTYPE($cmsMongo);
    $templates->get();	
    echo json_encode( array('templates'=> $templates->prepare() ) );
   	//echo $templates->toJSON();
?>
