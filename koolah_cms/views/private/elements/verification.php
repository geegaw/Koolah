<?php
$user = new SessionUser();
if ( !$user->status->success() ){
    $_SESSION['desired_page'] = $_SERVER['REQUEST_URI'];
    header("Location:".SIGNIN);
    exit;    
}

$user->getHistory();
?>
