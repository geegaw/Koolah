<?php
    $title = 'home';
    $js="fe/home";
    $css="home";
    include( ELEMENTS_PATH."/header.php" );
?>    
    <section id="home"> 
        <?php include( ELEMENTS_PATH."/home/mainCommands.php" ); ?>
        <?php include( ELEMENTS_PATH."/home/recent.php" ); ?>
    </section>
<?php include( ELEMENTS_PATH."/footer.php" ); ?>
