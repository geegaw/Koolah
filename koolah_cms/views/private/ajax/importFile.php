<?php
    /***
     * @name AJAX : import file
     * @var data : file * REQUIRED *
     * 
     * @return statusType,  msg: string
     */

    $status = new StatusTYPE();
    
    if (isset($_POST['data'])){
        $data = json_decode($_POST['data'], true);
        
        //verify properly formatted
        if (isset($data['classname']) && isset($data['data'])){
            $class = $data['classname'];
            if (class_exists($class)){
                $obj = new $class();
                $obj->read( $data['data'] );
                $status = $obj->save();
            }
            else
                $status->setFalse( 'file does not contain proper data' );
        }
        else
            $status->setFalse( 'file is not properly formatted' );
    }   
    else
        $status->setFalse( 'not enough information passed' );    
    
    echo json_encode( array( 'status'=>$status->success(), 'msg'=>$status->msg ) );
?>
