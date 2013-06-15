<?php
    //$active = array('admin', 'users');
    $css = array('slide');
    $js = array('slide');
    include( ELEMENTS_PATH."/header.php" );

    //debug::printr($page);
    $collection = new PageTYPE();
    $collection->getByID( $page->collection );
?> 

<section id="slide">

    <h1>Slide|<?php echo $page->name; ?></h1>
    <h2>Category:<?php echo $collection->name; ?></h2>
    <?php echo htmlTools::loadImage($page->photo ); //, 'landscape_2-full'); ?>    
    <?php if ( $page->description ) echo $page->description; ?>

</section>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>