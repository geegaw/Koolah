<?php
    $title = 'ratios';
    $js = array(
        'fe/ratios/main',
        'objects/types/ratios', 
     );
    $css = 'ratios';
    
    include( ELEMENTS_PATH."/header.php" );    
?>    

<section id="ratios">
    <?php include(ELEMENTS_PATH."/ratios/ratioList.php"); ?>
    <?php include(ELEMENTS_PATH."/ratios/ratioForm.php"); ?>
    <?php include(ELEMENTS_PATH."/ratios/ratioSizeForm.php"); ?>
</section>

<?php include( ELEMENTS_PATH."/footer.php" ); ?>