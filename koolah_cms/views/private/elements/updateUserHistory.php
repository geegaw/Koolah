<?php
    //global $user;
    if ( isset($title) ){
        $user->updateHistory($title, $_SERVER['REQUEST_URI']);
    }
    
?>