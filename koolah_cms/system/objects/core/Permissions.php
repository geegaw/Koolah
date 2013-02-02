<?php 

/*
$test = new Permissions();
foreach ( $test->get() as $cat=>$perm )
	Permissions::mkInput( $cat, $perm );
echo '<pre>'; print_r($test); echo '</pre>';  die;
*/
final class Permissions{
	
	private $permissions;
	private $sessionUser;
		
	public function __construct($user){
		$this->sessionUser = $user;
		if ( $this->sessionUser->status->success() )	
			$this->loadPermissions();	
	}
	
	
	private function loadPermissions(){
		require_once( CONF.'/permissions.php' );
		$this->permissions = $permissions;
	}
	
	
	public function get(){ return $this->permissions; }
	
	public function mkForm($class=null){
		foreach( $this->permissions as $cat=>$permissions ){
			Permissions::mkInput($cat, $permissions, null, $class);
		}
	}
		
	static public function mkInput( $cat, $permissions, $catRef=null, $class=null ){
		if( $permissions && is_array($permissions) ){
			if ( !$catRef )
				$catRef = $cat;
			$prefix = '';
			if ($class)
				$prefix = $class.'_';
			echo '<div class="cat '.$class.'">';
			echo 	'<div class="catName">'.$cat.'</div>';
			echo 	'<div class="catPermissions">';
			foreach ( $permissions as $key=>$permission ){
				if ( $permission === 'cmd' ){
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.'" type="checkbox" id="'.$prefix.$catRef.'_c" name="'.$catRef.'_c" value="'.$catRef.'_c" />';
					echo 	'<label for="'.$prefix.$catRef.'_c">Create</label>';
					echo '</fieldset>';
					
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.'" type="checkbox" id="'.$prefix.$catRef.'_m" name="'.$catRef.'_m" value="'.$catRef.'_m" />';
					echo 	'<label for="'.$prefix.$catRef.'_m">Modify</label>';
					echo '</fieldset>';
					
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.'" type="checkbox" id="'.$prefix.$catRef.'_d" name="'.$catRef.'_d" value="'.$catRef.'_d" />';
					echo 	'<label for="'.$prefix.$catRef.'_d">Delete</label>';
					echo '</fieldset>';
				}
				elseif( is_array($permission) ){
					self::mkInput( $key, $permission, $catRef.'_'.$key, $class );
				}
				else{
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.'" type="checkbox" id="'.$prefix.$catRef.'_'.$permission.'" name="'.$catRef.'_'.$permission.'" value="'.$catRef.'_'.$permission.'" />';
					echo 	'<label for="'.$prefix.$catRef.'_'.$permission.'">'.ucfirst($permission).'</label>';				
					echo '</fieldset>';
				}
			}
			echo 	'</div>';
			echo '</div>';
		}
		else{
			//TODO raise error
		}			
	}
	
}


?>