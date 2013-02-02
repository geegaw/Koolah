<?php
    session_start();
	require ( "init.php" );	
	Loader::serveReq( $_REQUEST );		
?>