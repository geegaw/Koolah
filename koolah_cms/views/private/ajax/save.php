<?php
 	global $cmsMongo;
	global $ajaxAccess;	
	$status = new StatusTYPE();
	if ( isset( $_POST['className']) && ( $_POST['className'] != 'null' )) {
		$class = $_POST['className'];
		if ( in_array($class, $ajaxAccess) ){
		    $obj = new $class($cmsMongo);
			if ( $obj->status->success() ){
				if (( isset( $_POST['id'])) && $_POST['id'] && ( $_POST['id'] != 'null' )) 
					$obj->getByID( $_POST['id'] );
				
                if (( isset( $_POST['data'])) && ( $_POST['data'] != 'null' )){
			    	$data = (array)json_decode($_POST['data']);
					$obj->read( $data );
//debug::printr($obj);
                    $status = $obj->save();
                    if ( $status->success() ){
                        $user = new SessionUser();    
                        //$user->updateHistoryAction('save', $class, $obj->getID());
                    }
                    echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg, 'id'=>$obj->getID() ) );
                    return;
				}
			    else
			  		$status->setFalse( 'not enough information passed-- no data' );
			}			
			else
				$status = cmsToolKit::permissionDenied();
		}			
		else
			$status = cmsToolKit::permissionDenied();
	}
	else
	  	$status->setFalse( 'not enough information passed-- no classname' );    
    echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg ) );
?>