<?php

define( 'SUPER_USER', 'superuser' );
define( 'ADMIN', 'admin' );

$roles = array(
	SUPER_USER,
	ADMIN=>array(
		'all',
	),
);

?>