<?php
    session_start();
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/UsersTYPE.php' );
    include_once( '../elements/objects/types/UserTYPE.php' );
    include_once( '../elements/objects/types/RolesTYPE.php' );
    include_once( '../elements/objects/types/RolesPermissionsTYPE.php' );    
    $user = new UserTYPE($cmsDB);
    if ($_SESSION['user'] == 1)
        $user->getRoot();
    else
        $user->getById( $_SESSION['user'] );
    
    //$roles = new RolesPermissionsTYPE($cmsDB);
    $roles = new RolesTYPE($cmsDB);
    $roles->getAll();
    
    if ($roles->numRoles > 0)
    {
        foreach ($roles->roles as $role)
        {
            echo '<li>
                    <div class="name">'.$role->label.'</div>';
            echo '  <div class="edit">';
            if ($user->hasPermission('edit_role') )
                echo '  <a href="'.$role->getID().'" class="edit">&nbsp;edit</a>';
            else
                echo '  &nbsp';
            echo '  </div>';
            
            echo '  <div class="del">';
            if ($user->hasPermission('del_role') )
                echo '  <a href="'.$role->getID().'" class="del">X</a>';
            else
                echo '  &nbsp';
            echo '  </div>';
            
            if ($user->hasPermission('view_roles_permissions') )
                echo '  <div class="hide permissions">'.$role->rolePermissions->JSONpermissionsIDs().'</div>';
            
            echo '</li>';
        } 
    }
?>
