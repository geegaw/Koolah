<?php
/**
 * preview
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
session_start();
require ( "init.php" ); 
if (!isset($_POST['secret_key']))
    header("Location:http://www.google.com");
if ($_POST['secret_key'] != PREVIEW_SECRET_KEY)
    header("Location:http://www.google.com");

if (!isset($_POST['template']) || !isset($_POST['data']))
    return; //TODO handle error

Router::servePreview( $_POST['template'], $_POST['data'] );      