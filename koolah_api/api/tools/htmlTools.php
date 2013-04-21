<?php

class htmlTools{
    
    static function loadImage( $id, $format=null, $download=false, $class='' ){
        $extras = '';
        if ( $format ){
            if (is_array($format) && key_exists('l', $format) && key_exists('p', $format)){
                $extras.='&formatP='.$format['p'];
                $extras.='&formatL='.$format['l'];
            }
            else
                $extras.='&format='.$format;
        }
        if ( $download )
            $extras.='&download='.$download;
        return '<img src="'.FM_URL.'?id='.$id.$extras.'" alt="'.'---'.'" class="'.$class.'" />';
    }
}

?>