<?php
  	global $cmsMongo;
	global $ajaxAccess;
	$status = new StatusTYPE();
	if( isset( $_POST['className']) && isset( $_POST['id'] )){
		$className = $_POST['className'];
		if ( in_array($className, $ajaxAccess) ){
			$obj = new $className($cmsMongo);
			if ( $obj->status->success() ){
				$id = $_POST['id'];
				if ( method_exists($obj, 'getByID') ){
					$obj->getByID( $id );
					echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg, 'node'=> $obj->prepare() ) );
					return;
				}			    	
				else	
					$status->setFalse("$className does not have neccessary methods");
			}				
			else
				$status = cmsToolKit::permissionDenied();	
		}			
		else
			$status = cmsToolKit::permissionDenied();
	}
	else
		$status->setFalse('Not enough Information passed');
	echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg ) );   	
?>
