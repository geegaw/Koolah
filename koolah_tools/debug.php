<?php
/**
 * debug
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * debug
 * 
 * helpful tools for debugging
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\tools
 */
class debug{

	/**
     * printr
     * print_r wrapped in <pre> tags 
     * can die if desired
     * @access public
     * @param mixed $t
     * @param bool $die
     */    
    static public function printr( $t, $die=false ){
		echo '<pre>'; print_r($t); echo '</pre>';
		if ( $die )
			die;
	}
	
	/**
     * vardump
     * var_dump wrapped in <pre> tags 
     * can die if desired
     * @access public
     * @param mixed $t
     * @param bool $die
     */    
    static public function vardump( $t, $die=false ){
		echo '<pre>'; var_dump($t); echo '</pre>';
		if ( $die )
			die;
	}
    
    /**
     * h1
     * text wrapped in <h1> tags 
     * can die if desired
     * @access public
     * @param string $t
     * @param bool $die
     */    
    static public function h1( $t, $die=false ){
        echo "<h1>$t</h1>";
        if ( $die )
            die;
    }
    
    /**
     * divClear
     * adds a div to add css clear:both 
     * can die if desired
     * @access public
     */    
    static public function divClear(){
        echo '<div class="clear""></div>';
    }

}
?>