<?php
    $mainNav = apiToolKit::getMenu('main_menu');
    $collections = apiToolKit::getPages('collection');
?>    
<div id="slideBar">
    
    <nav id="mainNav">
         <?php if ($mainNav):?>
            <?php foreach( $mainNav as $item ) :?>
                <a href="<?php echo $item->url; ?>" id="<?php echo $item->label->getRef(); ?>"><?php echo $item->label->label; ?></a>
            <?php endforeach; ?>
         <?php endif; ?>
    </nav>
    
    <nav id="collectionNav">
         <?php if ($collections):?>
            <?php foreach( $collections as $collection ) :?>
                <div class="collection">
                    <a href="<?php echo $collection->url; ?>" id="<?php echo $collection->label->getRef(); ?>">
                        <?php echo htmlTools::loadImage($collection->thumb, 'portrait_2-bio_thumb'); ?> 
                        <br /><?php echo $collection->label->label; ?>
                     </a>
                </div>
            <?php endforeach; ?>
            <div class="collection">
                    <a href="#" id="closeSubNav">&lt;</a>
                </div>
         <?php endif; ?>
    </nav>
            
    <div id="navInfo">
        <a id="footerLogo" href="/" />Home</a>
        <a href="http://www.facebook.com/pages/Vaugeois-Photography/108701089157136" id="facebook">Facebook</a>
        <div id="copyright">
            &copy;<?php echo date('Y'); ?> Vaugeois Photography
        </div>        
    </div>
</div>