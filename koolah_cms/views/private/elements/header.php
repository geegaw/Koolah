<?php 
	global $cmsMongo;   
    require(ELEMENTS_PATH.'/verification.php');
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php if ( isset($title) )echo $title.' | ' ?>koolah</title>
    
    <?php if (ENV == 'dev'): ?> <script type="text/javascript"> var less = { env: 'development'};</script> <?php endif; ?>
        
    <?php cmsToolKit::includeCSS( $css ); ?>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
    <?php	
    	cmsToolKit::includeJS( "lib/jquery.1.7.1.min" );
		cmsToolKit::includeJS( "lib/jquery-ui-1.8.17.custom.min" );
        if ( ENV=='dev' )
            cmsToolKit::includeJS( "lib/less.min" );
		if ( DEBUG )
			cmsToolKit::includeJS( "debug" );
		cmsToolKit::includeJS( "plugins" );
		
		include(PUBLIC_PATH.'/js/jsConf.php');
		cmsToolKit::includeJS( "global" );
        cmsToolKit::includeJS( "toolkit" );
		cmsToolKit::includeJS( "objects/core" );
        cmsToolKit::includeJS( "objects/elements/tools" );
        cmsToolKit::includeJS( "objects/elements/tabs" );
        cmsToolKit::includeJS( "objects/elements/actions" );
        if ( isset($js) )
        	cmsToolKit::includeJS( $js );
	?>
	
    
</head>
<body>
    <header>
        <div id="logo"><a href="<?php echo HOME ?>">logo</a></div>
        <div id="globalNav">
            <button type="button">
                <span>&nbsp;</span>
                <span>&nbsp;</span>
                <span>&nbsp;</span>
            </button>
        </div>
         <!--		
		<nav id="mainNav">
			<?php
				if (!isset($active))
					$active = '';
				include( NAVS_PATH.'/mainNav.php' );
				$mainNav = new MenuTYPE( $cmsMongo, 'mainNav' );
				$mainNav->read( array('menuItems'=>$mainNavBson) );
				//debug::printr( $mainNav, true );
				$mainNav->display( $active, 'div', true );
			?>        			
		</nav>
       
        <nav id="userOptions">
            <a href="editInfo.php"><?php echo $user->getUsername() ?></a>
            <a href="<?php echo SIGNOUT ?>">sign out</a>
        </nav>
        -->        
    </header>
    <nav id="mainNav" class="hide">
        <?php
            if (!isset($active))
                $active = '';
            include( NAVS_PATH.'/mainNav.php' );
            $mainNav = new MenuTYPE( $cmsMongo, 'mainNav' );
            $mainNav->read( array('menuItems'=>$mainNavBson) );
            //debug::printr( $mainNav, true );
            $mainNav->display( $active, 'div', true );
        ?>   
        <div  id="accountOptions">        
            <div class="menuItem"><a href="editInfo.php">account<?php //echo $user->getUsername() ?></a></div>
            <div class="menuItem"><a href="<?php echo SIGNOUT ?>">sign out</a></div>
        </div>
    </nav>
    <?php flush(); ?>