<?php
    $title = 'uploads';
    $js = array(
        'plugins/jquery.Jcrop.min', 
        'fe/uploadCenter/main',
        'objects/types/tags',
        'objects/types/uploads',
        'objects/types/ratios', 
     );
    $css = 'uploadCenter';
    include( ELEMENTS_PATH."/header.php" );
?>    
<section id="uploads">        
    <?php include( ELEMENTS_PATH.'/files/filesSection.php'  ); ?>
    <?php include( ELEMENTS_PATH.'/uploadCenter/tagsSection.php'  ); ?>    
</section>
<?php include( ELEMENTS_PATH.'/uploadCenter/cropSection.php'  ); ?>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>