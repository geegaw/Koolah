<?php
	$mainNavBson = array(
		array( 
			"name"=> "website",
			"url"=> null,
			"menuItems"=>array(
				array( 
					"name"=> 'pages',
					"url"=> '/pages',
					"menuItems"=> null,
					"permission"=> 'pages_m',
				),
				array( 
					"name"=> 'menus',
					"url"=> '/menus',
					"menuItems"=> null,
					"permission"=> 'menus_m',
				),
				array( 
					"name"=> 'taxonomy',
					"url"=> '/taxonomy',
					"menuItems"=> null,
					"permission"=> 'taxonomy_m',
				),
			), 
			"permission"=> '',
		),
		/*
		array( 
			"name"=> "social",
			"url"=> null,
			"menuItems"=> null,	
			"permission"=> '',		 
		),
		 * 
		 */
		array( 
			"name"=> "upload Center",
			"url"=> '/uploadcenter',
			"menuItems"=> null,
			"permission"=> 'files_m',			 
		),
		array( 
			"name"=> "admin",
			"url"=> null,
			"menuItems"=>array(
				array( 
					"name"=> 'stats',
					"url"=> null,
					"menuItems"=> null,
					"permission"=> 'stats_view',
				),
				array( 
					"name"=> 'ratios',
					"url"=> '/ratios',
					"menuItems"=> null,
					"permission"=> 'ratios_m',
				),
				array( 
					"name"=> 'users',
					"url"=> '/users',
					"menuItems"=> null,
					"permission"=> 'users_m',
				),
				array( 
					"name"=> 'roles',
					"url"=> '/roles',
					"menuItems"=> null,
					"permission"=> 'roles_m',
				),
				array( 
					"name"=> 'templates',
					"url"=> null,
					"menuItems"=> array(
						array( 
							"name"=> 'pages',
							"url"=> '/templates',
							"menuItems"=> null,
							"permission"=> 'templates_m',
						),
						array( 
							"name"=> 'widgets',
							"url"=> '/widgets',
							"menuItems"=> null,
							"permission"=> 'templates_m',
						),
						array( 
		                    "name"=> 'fields',
		                    "url"=> '/fields',
		                    "menuItems"=> null,
		                    "permission"=> 'templates_m',
		                ),
					),
					"permission"=> 'templates_m',
				),
				
			), 
			"permission"=> '',
		),
	);
	
?>