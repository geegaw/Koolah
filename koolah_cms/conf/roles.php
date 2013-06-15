<?php
/**
 * roles
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * roles
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms\conf
 */ 
define( 'SUPER_USER', 'superuser' );
define( 'ADMIN', 'admin' );

$roles = array(
	SUPER_USER,
	ADMIN=>array(
		'all',
	),
);

?>