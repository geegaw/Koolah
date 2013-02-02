<?php
    /***
     * @name AJAX : Upload Element
     * @var id : mongodb id * REQUIRED *
     * @var className : string * REQUIRED *
     * @var file : file * REQUIRED *
     * 
     * @return statusType,  msg: string
     */

    global $cmsMongo;
    global $ajaxAccess; 
    $status = new StatusTYPE();
    if ( isset( $_POST['className']) && ( $_POST['className'] != 'null' )) {
        $class = $_POST['className'];
        if ( in_array($class, $ajaxAccess) ){
            $obj = new $class($cmsMongo);
            if ( $obj->status->success() ){
                if ( method_exists($obj, 'upload') ){
                    if (isset( $_POST['id'] ) 
                         && $_POST['id'] 
                         && $_POST['id'] != 'null'
                         && isset($_FILES["file"])
                    ){ 
                        $obj->getByID( $_POST['id'] );
                        $status = $obj->upload( $_FILES["file"] );
                        if ( $status->success() ){
                            $user = new SessionUser();
                            //$user->updateHistoryAction('upload', $class, $obj->getID());
                        }
                    }
                    else
                        $status->setFalse( 'not enough information passed-- no id or file' );
                }
                else//if ( method_exists($obj, 'upload') )
                      $status->setFalse( "$class cannot upload" );
            }           
            else//if ( $obj->status->success() )
                $status = cmsToolKit::permissionDenied();
        }           
        else//if ( in_array($class, $ajaxAccess) )
            $status = cmsToolKit::permissionDenied();
    }
    else//if ( isset( $_POST['className']) && ( $_POST['className'] != 'null' ))
        $status->setFalse( 'not enough information passed-- no classname' );    
    
    
    echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg ) );
?>
