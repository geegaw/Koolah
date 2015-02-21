<?php
	debug::h1($_SERVER['REQUEST_METHOD'], 1);
    header('Content-type: application/json');
    
	global $cmsMongo;
	global $ajaxAccess;
	
	$status = new StatusTYPE();
	if( isset( $_POST['className']) && $_POST['className']!= null ){
		$className = $_POST['className'];
		if ( in_array($className, $ajaxAccess) ){
			$obj = new $className($cmsMongo);
			if ( $obj->status->success() ){
				if ( method_exists($obj, 'get') ){
				    $orderBy = null;    
				    if(isset( $_POST['orderBy'] ) && $_POST['orderBy']!= null)
				            $orderBy = $_POST['orderBy']; 
					if(isset( $_POST['args'] ) && $_POST['args']!= null){
						//$q = customMongo::jsParseWhere( $_POST['args'] );	
						$obj->get( $_POST['args'], null, $orderBy );
					}
					else	
						$obj->get(null, null, $orderBy);
					echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg, 'nodes'=> $obj->prepare() ) );
					return;
				}			    	
				else	
					$status->setFalse("$className does not have neccessary methods");
			}
			else
				$status = cmsToolKit::permissionDenied('object does not exist');
		}
		else
			$status = cmsToolKit::permissionDenied('not in array');
	}
	else
		$status->setFalse('Not enough Information passed');
	echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg ) );   	
?>
