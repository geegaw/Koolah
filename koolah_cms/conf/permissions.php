<?php
/**
 * permissions
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * permissions
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\cms\conf
 */ 
$permissions = array(
	"pages" => array( 
		'cmd', 
		'publish',
	),
	"widgets" => array( 
		'cmd', 
	),
	"menus" => array( 
        'cmd', 
    ),
	"taxonomy" => array( 
		'cmd', 
	),
	"files" => array( 
        'cmd', 
        'crop',
    ),
	"stats" => array( 
		'view',
	),
	"ratios" => array( 
		'cmd',
	),
	"users" => array( 
		'cmd',
	),
	/*
	"roles" => array( 
		'cmd',
		//'grant',
	),
	//"permissions" => array( 
	//	'grant',
	//),
	 * 
	 */
	/*
	"folders" => array( 
        'cmd', 
    ),
	 * 
	 */
    "templates" => array( 
        'pages'=>array(
            'cmd',
        ),
        'widgets'=>array(
            'cmd',
        ),
        'fields'=>array(
            'cmd',
        ),
    ),
    

);


?>