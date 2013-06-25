<?php
    if (!isset( $_REQUEST['classname']) || !isset( $_REQUEST['id'])){
        echo '<h1>Missing Parameters</h1>';
        die;
    }
    
    $element = new $_REQUEST['classname']();
    $element->getByID( $_REQUEST['id'] );
    $json = $element->toJSON(); 
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$element->label->getRef().'.kol');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    echo $json;
?>