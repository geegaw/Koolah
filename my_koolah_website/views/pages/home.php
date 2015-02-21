<?php
    $css = array('home');
    include( ELEMENTS_PATH."/header.php" );
?> 
<section id="mainBody">
	<div id="mainImage"><?php echo htmlTools::loadImage($page->background_image); ?></div>
	<img id="logo" src="/public/img/logo/logo.png" alt="VAUGEOIS PHOTOGRAPHY" />
</section>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>