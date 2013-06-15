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
	"users" => array( 
		'cmd',
	),
	"roles" => array( 
		'cmd',
		//'grant',
	),
	//"permissions" => array( 
	//	'grant',
	//),
	"tags" => array( 
		'cmd', 
	),
	"pages" => array( 
		'cmd', 
		'publish',
	),
	"widgets" => array( 
		'cmd', 
	),
	"folders" => array( 
        'cmd', 
    ),
    "menus" => array( 
        'cmd', 
    ),
	"files" => array( 
	   'file'=>array(
            'cmd', 
            'crop',
        ), 
        'tags'=>array(
            'cmd',
        ),
    ),
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