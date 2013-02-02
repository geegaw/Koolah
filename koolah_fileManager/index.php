<?php
    session_start();
	require ( "init.php" );	
	Router::serveReq( $_REQUEST );		
?>