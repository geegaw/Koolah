<?php
    //$active = array('admin', 'users');
    //$css = array('userRoles', 'roles');
    //$js = array('objects/types/roles', 'roles', 'permissions');
    include( ELEMENTS_PATH."/header.php" );

    //debug::printr($page);
?> 

<section id="slide">

    <h1>Slide|<?php echo $page->name; ?></h1>
    <?php echo htmlTools::loadImage($page->photo, 'landscape_2-full'); ?>    
    <?php if ( $page->description ) echo $page->description; ?>

</section>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>