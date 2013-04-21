<?php
    $css = array('home');
    $js = array('home');
    include( ELEMENTS_PATH."/header.php" );
?> 
<div id="mainImage"><?php echo htmlTools::loadImage($page->background_image); ?></div>
<img id="logo" src="/public/img/logo/logo.png" alt="VAUGEOIS PHOTOGRAPHY" />
<?php include( ELEMENTS_PATH."/footer.php" ); ?>