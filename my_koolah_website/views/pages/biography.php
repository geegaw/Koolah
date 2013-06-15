<?php
    $active = array('bio');
    $css = array('bio');
    include( ELEMENTS_PATH."/header.php" );
?> 

<div id='biography'>
       <h1> Biography </h1>
       <?php echo $page->bio ?>       
</div>
<div class="rightImage"><?php echo htmlTools::loadImage($page->background_image); ?></div>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>