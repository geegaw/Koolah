<?php
    include_once('../elements/objects/customMySQL.php');
    include ('../../data.php');
    $cmsDB = new customMySQL( DB_USER, DB_PASS, DB_HOST, DB_NAME );
    include_once( '../elements/objects/types/UserTYPE.php' );
    include_once( '../elements/objects/types/RolesTYPE.php' );
    include_once( '../elements/objects/types/RoleTYPE.php' );
    
    $user = new UserTYPE($cmsDB);
    if ( isset( $_POST['username']))
        $user->setUsername( $_POST['username'] );

    if ( isset( $_POST['name']))
        $user->setName( $_POST['name'] );
    
    if ( isset( $_POST['pass']))
        $user->setPass( $_POST['pass'] );
    
    if ( isset( $_POST['roles']))
        $user->userRoles->set( $_POST['roles'] );
                   
    if ( isset( $_POST['custom']))
    {
        $customRole = new RoleTYPE($cmsDB);
        $customRole->rolePermissions->set( $_POST['custom'] );        
        $customRole->label = 'custom';        
        $customRole->setName( 'custom' );
        $user->userRoles->append( $customRole );
    }

    if ( $status = $user->save() )
    {
        //if ($status == 'username already exists')
          //  echo json_encode( array('status'=>'username already exists') );
        //else
            echo json_encode( array('status'=>'success') );
    }
    else
        echo json_encode( array('status'=>'an error occrued while saving') );
?>
