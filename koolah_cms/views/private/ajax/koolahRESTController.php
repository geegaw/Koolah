<?php
global $ajaxAccess;
$controller = new KoolahRESTController($ajaxAccess);
$controller->dispatch();

class KoolahRESTException extends Exception{}
class KoolahRESTPermissionException extends Exception{}

class KoolahRESTController{
	private $status;
	
	public function __construct($ajaxAccess){
		$this->status = new StatusTYPE();
		$this->ajaxAccess = $ajaxAccess;
	}
	
	public function dispatch(){
		$obj = null;
		try{
			if (!isset($_SERVER['REQUEST_METHOD']))
				throw new KoolahRESTException('no request method');

			$method = strtolower($_SERVER['REQUEST_METHOD']);
			if ($method == 'post')
				$method = 'put';
			if (!method_exists($this, $method))
				throw new KoolahRESTException('unkown request method: '.$method);
			$obj = $this->$method();
						
		}
		catch (KoolahRESTException $e){
			$this->status->setFalse( $e->getMessage() );
		}
		catch (KoolahRESTPermissionException $e){
			$this->status = cmsToolKit::permissionDenied( $e->getMessage() );
		}
		$this->renderResponse($obj);
	}
	
	private function get(){
		$obj = $this->validateObj();	
		
		$orderBy = koolahToolKit::getParam('orderBy', $_GET);
		$query = koolahToolKit::getParam('query', $_GET);
		$id = koolahToolKit::getParam('id', $_GET);
	    $page = (int) koolahToolKit::getParam('page', $_GET, 0);
		$limit = (int) koolahToolKit::getParam('limit', $_GET, MAX_PER_PAGE);
		$offset = $page * $limit;
		
		if ($id)
			$obj->getById( $id );
		elseif($query)
			$obj->get( $query, null, $orderBy, $offset, $limit );
		else	
			$obj->get(null, null, $orderBy, $offset, $limit);
		
		return $obj;
	}
	
	private function put(){
		$obj = $this->validateObj();		
		$data = $this->getStreamData();
		
		if (isset($data['id']) && $data['id'] && $data['id'] != 'null'){ 
			$obj->getByID( $data['id'] );
			unset($data['id']);
		}
	
    	$obj->read( $data );
        $status = $obj->save();
        if ( !$status->success() )
			throw new KoolahRESTException( $status->msg );

        $user = new SessionUser();    
        //$user->updateHistoryAction('save', $class, $obj->getID());
        return $obj;
	}
	
	private function delete(){
		$obj = $this->validateObj();		
		
		if (!(isset($_GET['id']) && $_GET['id'] && $_GET['id'] != 'null'))
			throw new KoolahRESTException('no id passed'); 
		
		$obj->getByID( $_GET['id'] );
		$status = $obj->del();
		
		if ( !$status->success() )
			throw new KoolahRESTException( $status->msg );
        $user = new SessionUser();
        //$user->updateHistoryAction('del', $class, $obj->getID());
        			
		return null;
	}
	
	private function getStreamData(){
		return json_decode(file_get_contents('php://input'), true);
	}
	
	private function validateObj(){
		if( !isset( $_REQUEST['className']))
			throw new KoolahRESTException('Not enough Information passed');
		if ( $_REQUEST['className'] == null )
			throw new KoolahRESTException('Not enough Information passed');
			
		$className = $_GET['className'];
		if ( !in_array($className, $this->ajaxAccess) )
			throw new KoolahRESTPermissionException('object access error');
			
		$obj = new $className();
		if ( !$obj->status->success() )
			throw new KoolahRESTPermissionException('object does not exist');
		
		if (!method_exists($obj, 'get') )
			throw new KoolahRESTException("$className does not have neccessary methods");
		
		return $obj;
	}
	
	private function renderResponse($result=null){
		$response =	array( 
			'status'=>$this->status->success(), 
			'msg'=>$this->status->msg 
		);
		
		if ($result){
			$response = $result->prepare();	
			if (method_exists($result, 'total')){
				$response = array(
					'nodes' => $response,
					'total' => $result->total,
				); 
			}
		
			
			
		}
		elseif (!$this->status->success())
			header("HTTP/1.1 500 Internal Server Error");
		
		header('Content-type: application/json');  
		echo json_encode($response);  
	}
}
