<?php
/**
 * koolahToolKit
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * koolahToolKit
 * 
 * CMS tools
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\tools
 */
class htmlTools{
    
    /**
     * loadImage
     * load an image from the file manager
     * can pass either one format or an array if
     * you wish to accomadate landscape vs portrait
     * can also include aditonal classes for the img
     * @access public 
     * @static
     * @param string $id
     * @param string|array $format - optional
     * @param bool $download - optional
     * @param string $class - optinal
     * @return string
     */    
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