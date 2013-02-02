<?php 
    $user = new SessionUser();
    $user->signout();
    
    header("Location:".SIGNIN);
    exit; 
?>
