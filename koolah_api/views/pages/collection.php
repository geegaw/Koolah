<?php
    $slides = $collections = cmsToolKit::getPages('slide', array('collection'=>$page->getID()));    
    
    $css = array('collection');
    $js = array('collection');
    include( ELEMENTS_PATH."/header.php" );
?> 


<div id="slides">
    <?php foreach ($slides as $slide): ?>
        <div id="slide_<?php echo $slide->getID(); ?>" class="slideInfo  hide" data-name="<?php echo $slide->name;?>">
            <?php echo htmlTools::loadImage($slide->photo); ?>
        </div>
    <?php endforeach; ?>
</div>

<div id="thumbs">
    <?php foreach ($slides as $slide): ?>
        <div class="slide">
            <a href="<?php echo $slide->url; ?>" data-id="<?php echo $slide->getID(); ?>"> 
                <?php echo htmlTools::loadImage($slide->photo, array('p'=>'portrait_2-bio_thumb', 'l'=>'landscape_2-thumb')); ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>


<?php include( ELEMENTS_PATH."/footer.php" ); ?>