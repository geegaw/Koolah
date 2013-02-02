<?php

class htmlTools{
    
    static function loadImage( $id, $format=null, $download=false ){
        $extras = '';
        if ( $format )
            $extras.='&format='.$format;
        if ( $download )
            $extras.='&download='.$download;
        return '<img src="'.FM_URL.'?id='.$id.$extras.'" alt="'.'---'.'" />';
    }
}

?>