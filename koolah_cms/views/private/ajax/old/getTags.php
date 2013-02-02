<?php
    session_start();
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/TagsTYPE.php' );
    
    $tags = new TagsTYPE($cmsDB);
    $tags->getAll();
    echo $tags->jsonEncode();
?>
