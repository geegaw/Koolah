<?php
/**
 * index
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
    session_start();
    require ( "init.php" ); 
    Router::serveReq( $_REQUEST );  
?>