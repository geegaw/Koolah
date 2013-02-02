<?php
    session_start();
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/FilesTYPE.php' );
    
    $files = new FilesTYPE($cmsDB);
    $files->getAll();
    echo $files->jsonEncode();
?>
