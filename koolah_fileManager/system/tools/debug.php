<?php

class debug{

	static public function printr( $t, $die=false ){
		echo '<pre>'; print_r($t); echo '</pre>';
		if ( $die )
			die;
	}
	
	static public function vardump( $t, $die=false ){
		echo '<pre>'; var_dump($t); echo '</pre>';
		if ( $die )
			die;
	}

}
?>