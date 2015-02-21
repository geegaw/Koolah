<?php 
	global $cmsMongo;   
    require(ELEMENTS_PATH.'/verification.php');
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/public/js/lib/select2/select2.css"/>
    <?php koolahToolKit::includeCSS( 'koolah', true ); ?>    
    <?php if (isset($css))koolahToolKit::includeCSS($css ); ?>
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
    <?php include(PUBLIC_PATH.'/js/jsConf.php');; ?>
    <script data-main="/public/js/main" src="/public/js/lib/require/require.min.js"></script>
    
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
    <nav id="mainNav" class="menuItem hide">
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
            	<?php if ($user->history->length()): ?>
	                <?php foreach( $user->history->pageVisits(10, 0, true) as $pageVisit ): ?>
	                    <div class="menuItem"><a href="<?php echo $pageVisit->url; ?>"><?php echo $pageVisit->title; ?></a></div>
	                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>  
        <div  id="accountOptions">        
            <div class="menuItem"><a href="/account">my account</a></div>
            <div class="menuItem"><a href="<?php echo SIGNOUT ?>">sign out</a></div>
        </div>
    </nav>
    <?php flush(); ?>