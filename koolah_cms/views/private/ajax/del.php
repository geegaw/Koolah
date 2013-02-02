<?php
    global $ajaxAccess;
	$status = $cmsMongo->status;
    $user = new SessionUser();
    
	if ( $status->success() ){
		if ( isset( $_POST['id']) && isset($_POST['className']) ){
			$class = $_POST['className'];	
			if ( in_array($class, $ajaxAccess) ){
				$obj = new $class();	
				if ( $obj->status->success() ){	
					$id = $_POST['id'];
					$obj->getByID( $id );
					$status = $obj->del();
                    if ( $status->success() ){
                        $user = new SessionUser();
                        //$user->updateHistoryAction('del', $class, $obj->getID());
                    }     
				}
				else
					$status = cmsToolKit::permissionDenied();
			}
			else
				$status = cmsToolKit::permissionDenied();
	    }
		else 
			$status->setFalse( 'Not enough information passed to me' );
	}			
    echo json_encode( array('status'=> $status->success(), 'msg'=>$status->msg) );
?>
