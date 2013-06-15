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
        
    <?php koolahToolKit::includeCSS($css ); ?>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
    <?php	
    	koolahToolKit::includeJS( "lib/jquery.1.7.1.min" );
		koolahToolKit::includeJS( "lib/jquery-ui-1.8.17.custom.min" );
        if ( ENV=='dev' )
            koolahToolKit::includeJS( "lib/less.min" );
		if ( DEBUG )
			koolahToolKit::includeJS( "debug" );
		koolahToolKit::includeJS( "plugins" );
		
		include(PUBLIC_PATH.'/js/jsConf.php');
		koolahToolKit::includeJS( "global" );
        koolahToolKit::includeJS( "toolkit" );
		koolahToolKit::includeJS( "objects/core" );
        koolahToolKit::includeJS( "objects/elements/tools" );
        koolahToolKit::includeJS( "objects/elements/tabs" );
        koolahToolKit::includeJS( "objects/elements/actions" );
        if ( isset($js) )
        	koolahToolKit::includeJS( $js );
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
    </header>
    <nav id="mainNav" class="hide">
        <?php
            if (!isset($active))
                $active = '';
            include( NAVS_PATH.'/mainNav.php' );
            $mainNav = new MenuTYPE( $cmsMongo, 'mainNav' );
            $mainNav->read( array('menuItems'=>$mainNavBson) );
            $mainNav->display( $active, 'div', true );
        ?> 
        <div class="menuItem">
            <a href="#"  class="subMenuTrigger">recent</a>
            <div class="subMenu hide">
                <?php foreach( $user->history->pageVisits(10, 0, true) as $pageVisit ): ?>
                    <div class="menuItem"><a href="<?php echo $pageVisit->url; ?>"><?php echo $pageVisit->title; ?></a></div>
                <?php endforeach; ?>
            </div>
        </div>  
        <div  id="accountOptions">        
            <div class="menuItem"><a href="editInfo.php">my account</a></div>
            <div class="menuItem"><a href="<?php echo SIGNOUT ?>">sign out</a></div>
        </div>
    </nav>
    <?php flush(); ?>