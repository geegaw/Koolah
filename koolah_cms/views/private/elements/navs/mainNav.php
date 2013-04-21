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
					"permission"=> '',
				),
				array( 
					"name"=> 'menus',
					"url"=> '/menus',
					"menuItems"=> null,
					"permission"=> '',
				),
				array( 
					"name"=> 'taxonomy',
					"url"=> null,
					"menuItems"=> null,
					"permission"=> '',
				),
			), 
			"permission"=> '',
		),
		array( 
			"name"=> "social",
			"url"=> null,
			"menuItems"=> null,	
			"permission"=> '',		 
		),
		array( 
			"name"=> "upload Center",
			"url"=> '/uploadCenter',
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
					"permission"=> '',
				),
				array( 
					"name"=> 'ratios',
					"url"=> '/ratios',
					"menuItems"=> null,
					"permission"=> '',
				),
				array( 
					"name"=> 'users',
					"url"=> '/users',
					"menuItems"=> null,
					"permission"=> 'users_m',
				),
				array( 
					"name"=> 'developer',
					"url"=> '/developer',
					"menuItems"=> null,
					"permission"=> '',
				),
				array( 
                    "name"=> 'system',
                    "url"=> '/system',
                    "menuItems"=> null,
                    "permission"=> '',
                ),
			), 
			"permission"=> '',
		),
	);
	
?>