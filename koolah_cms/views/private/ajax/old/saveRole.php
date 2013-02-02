<?php
 	global $cmsMongo;   
    $role = new RoleTYPE($cmsMongo);
    
	
	$status = new StatusTYPE();
	if (( isset( $_POST['id'])) && ( $_POST['id'] != 'null' ))
        $role->getByID( $_POST['id'] );
        
    if (( isset( $_POST['name'])) && ( $_POST['name'] != 'null' ))
        $role->label->label = $_POST['name'] ;
	else 
		$status->setFalse('requires a  name');
	
    if (( isset( $_POST['permissions'])) && ( $_POST['permissions'] != 'null' ))
        $role->permissions = $_POST['permissions'];
    
	if ( $status->success() )
		$status = $role->save();
	  
    echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg ) );
?>
