<?php 
    global $cmsMongo;
    
    if ( isset($title) ) 
        $title =  '| '.$title;
    elseif (isset($page) && $page->seo && $page->seo->title)
        $title =  '| '.$page->seo->title;
    else
        $title = '';
    
    if (!isset($description) ){
        if (isset($page) && $page->seo && $page->seo->description)
            $description = $page->seo->description;
        else 
            $description = '';
    }
?>


<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vaugeois Photography <?php echo $title; ?></title>
    <meta name="description" content="<?php echo $description; ?>"> 
    
    <?php if (ENV == 'dev'): ?> <script type="text/javascript"> var less = { env: 'development'};</script> <?php endif; ?>
        
    <?php if ( isset($css) ) cmsToolKit::includeCSS( $css ); ?>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    
    <?php   
        cmsToolKit::includeJS( "lib/jquery.1.7.1.min" );
        cmsToolKit::includeJS( "lib/jquery-ui-1.8.17.custom.min" );
        if ( ENV=='dev' )
            cmsToolKit::includeJS( "lib/less.min" );
        //if ( DEBUG )
            //cmsToolKit::includeJS( "debug" );
        //cmsToolKit::includeJS( "plugins" );
        
        include(PUBLIC_PATH.'/js/jsConf.php');
        cmsToolKit::includeJS( "global" );
        //cmsToolKit::includeJS( "toolkit" );
        if ( isset($js) )
            cmsToolKit::includeJS( $js );
    ?>
    
    
</head>
<body>
    <header>
    </header>    
    <?php flush(); ?>