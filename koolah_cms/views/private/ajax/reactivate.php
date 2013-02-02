<?php
    global $cmsMongo;
	$status = $cmsMongo->status;
	if ( $status->success() ){
		if ( isset( $_POST['id']) && isset($_POST['className']) ){
			$id = $_POST['id'];
			$class = $_POST['className'];	
			$obj = new $class( $cmsMongo );
			$obj->getByID( $id );
			$status = $obj->reactivate(true);
            if ( $status->success() ){
                $user = new SessionUser();
                //$user->updateHistoryAction('reactivate', $class, $obj->getID());
            }
	    }
		else 
			$status->setFalse( 'Not enough information passed to me' );
	}			
    echo json_encode( array('status'=> $status->success(), 'msg'=>$status->msg) );
?>
