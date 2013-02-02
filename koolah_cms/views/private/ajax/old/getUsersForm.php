<?php
    session_start();
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/UsersTYPE.php' );
    include_once( '../elements/objects/types/UserTYPE.php' );
    
    $user = new UserTYPE($cmsDB);
    
    if ($_SESSION['user'] == 1)
        $user->getRoot();
    else
        $user->getById( $_SESSION['user'] );
    
    $users = new UsersTYPE($cmsDB);
    if ($user->isRoot() )
    {
        echo '<li>
                <div class="username">'.$user->getUsername().'</div>
                <div class="name">'.$user->getName().'</div>
                <div class="edit"><a href="1" class="edit">&nbsp;edit</a></div>
             </li>';
        $users->getAll(false);
    }
    else
        $users->getAll();
    
    
    if ($users->numUsers > 0)
    {
        foreach ($users->users as $auser)
        {
            if ( $user->isRoot() && (!$auser->isActive() ) )
                echo '<li class="inactive">';
            else
                echo '<li>';
            echo '  <div class="username">'.$auser->getUsername().'</div>
                    <div class="name">'.$auser->getName().'</div>';
            
            echo '  <div class="edit">';
            if ($user->hasPermission('edit_user') )
                echo '  <a href="'.$auser->getID().'" class="edit">&nbsp;edit</a>';
            else
                echo '  &nbsp';
            echo '  </div>';
            
            echo '  <div class="del">';
            if ($user->hasPermission('del_user') && ($auser->getID() != $user->getID()) )
                echo '  <a href="'.$auser->getID().'" class="del">X</a>';
            else
                echo '  &nbsp';
            echo '  </div>';
           
            if ($user->hasPermission('view_roles_permissions') )
                echo '  <div class="hide roles">'.$auser->userRoles->JSONRoles().'</div>';
            echo '</li>';
        } 
    }     
?>
