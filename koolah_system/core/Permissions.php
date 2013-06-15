<?php 
/**
 * Permissions
 * 
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */ 
/**
 * Permissions
 * 
 * User permissions tools
 * 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core
 */ 
final class Permissions{
	
	/**
     * list of permissions
     * @var array
     * @access  private
     */ 
    private $permissions;
	
	/**
     * the logged in user
     * @var SessionUser
     * @access  private
     */ 
    private $sessionUser;
		
	/**
     * constructor
     * 
     * loads sersionsUsers permissions
     * @param UserTYPE user
     */
    public function __construct($user){
		$this->sessionUser = $user;
		if ( $this->sessionUser->status->success() )	
			$this->loadPermissions();	
	}
	
	
	/**
     * loadPermissions
     * 
     * loads config permissions
     * @access private
     */
    private function loadPermissions(){
		require_once( CONF.'/permissions.php' );
		$this->permissions = $permissions;
	}
	
	
	/**
     * get
     * 
     * return permissions
     * @access public
     * @return assocArray 
     */
    public function get(){ return $this->permissions; }
	
	/**
     * mkForm
     * 
     * makes a permission form adding an optional class
     * @access public
     * @param string $class -- optional
     * @return assocArray 
     */
    public function mkForm($class=null){
		foreach( $this->permissions as $cat=>$permissions ){
			Permissions::mkInput($cat, $permissions, null, $class);
		}
	}
		
	/**
     * mkInput
     * 
     * makes a permission form adding an optional class
     * @access public
     * @param string $cat
     * @param assocArray $permissions
     * @param string $catRef
     * @param string $class -- optional
     */
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
					echo 	'<input class="permission '.$class.' noreset" type="checkbox" id="'.$prefix.$catRef.'_c" name="'.$catRef.'_c" value="'.$catRef.'_c" />';
					echo 	'<label for="'.$prefix.$catRef.'_c">Create</label>';
					echo '</fieldset>';
					
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.' noreset" type="checkbox" id="'.$prefix.$catRef.'_m" name="'.$catRef.'_m" value="'.$catRef.'_m" />';
					echo 	'<label for="'.$prefix.$catRef.'_m">Modify</label>';
					echo '</fieldset>';
					
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.' noreset" type="checkbox" id="'.$prefix.$catRef.'_d" name="'.$catRef.'_d" value="'.$catRef.'_d" />';
					echo 	'<label for="'.$prefix.$catRef.'_d">Delete</label>';
					echo '</fieldset>';
				}
				elseif( is_array($permission) ){
					self::mkInput( $key, $permission, $catRef.'_'.$key, $class );
				}
				else{
					echo '<fieldset class="permission '.$class.'">';
					echo 	'<input class="permission '.$class.' noreset" type="checkbox" id="'.$prefix.$catRef.'_'.$permission.'" name="'.$catRef.'_'.$permission.'" value="'.$catRef.'_'.$permission.'" />';
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