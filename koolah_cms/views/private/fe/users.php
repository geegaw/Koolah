<?php
    /***
	 * Header info
	 */
    $active = array('admin', 'users');
    $title = 'users-admin';
    $css = array('userRoles', 'users');
	$js = array('objects/types/roles', 'objects/types/users', 'users' );
    include( ELEMENTS_PATH."/header.php" );
	/***/
	
	/***
	 * Navs
	 */
	$adminNav = new MenuTYPE( $cmsMongo, 'adminNav' );
	include( NAVS_PATH.'/adminOptions.php' );
	$adminNav->read( array('menuItems'=>$adminNavBson) );
	/***/		
	
	/***
	 * Page info
	 */
	$colors = array('blue', 'green', 'gold', 'purple', 'orange');
	$numColors = count($colors);
	$permissions = new Permissions($user);
	$roles = new RolesTYPE($cmsMongo);
    $roles->get();
	/***/
?>    
<section id="users">        
	<div class="leftBlock"> 
		<?php $adminNav->display( $active, 'div', true ); ?> 
	</div>
    
    <div class="mainBlock">
        <div id="msgBlock"></div>
        <?php if ($user->can('add_user') ): ?><div id="addNewUser"><a href="">Add New User</a></div><?php endif ?>      
        <ul id="usersList"></ul>
    </div>
            
    <form id="userForm" class="usersRolesForm hide">
    	<div id="leftCol">    
            <fieldset id="userInfoArea">
                <legend>User Info:</legend>
		    	<fieldset>
		    		<label for="userName">User Name</label>
		    		<input type="text"  id="userName" class="required email" value="" placeholder="User Name" />	
		    	</fieldset>
		    	
		    	<fieldset>
		    		<label for="name">Name</label>
		    		<input type="text"  id="name" class="required" value="" placeholder=" Name" />	
		    	</fieldset>
		    	
		    	<fieldset class="confirmation">
		    		<fieldset>
		        		<label for="pass1">Password</label>
		        		<input type="password"  id="pass1" class="confirmation1" value="" />
		        		<div class="description">Password must 6-12 charachters long and contain at least one number.</div>	
		        	</fieldset>
		        	<fieldset>
		        		<label for="pass2">Confirm Password</label>
		        		<input type="password"  id="pass2" class="confirmation2" value="" />	
		        	</fieldset>
		    	</fieldset>
		    	
			</fieldset>                    
        </div>
        
        <div id="rightCol">
            <fieldset id="saveCancel">
           			<input type="submit" class="cancel" id="cancel" value="Cancel" />
		        	<input type="submit" class="reset" id="reset" value="Reset" />
		        	<input type="submit" class="save submit" id="save" value="Save" />
		        	<input type="hidden" id="userID" value="" />
            </fieldset>
            
            <fieldset id="rolesArea">
                <legend>Roles:</legend>
                
                <?php if ($user->isSuper()) : ?>
                	<fieldset  class="role">
						<input type="checkbox" id="superuser" class="role userRole" value="superuser" />
						<label for="superuser">Super User</label>
						<?php cmsToolKit::mkHelp('Super Users have all permissions. Only superuses can delete users permanately'); ?>
					</fieldset>
                <?php endif; ?>
                
                <?php if ($user->isAdmin()) : ?>
                	<fieldset  class="role">
						<input type="checkbox" id="admin" class="role userRole" value="admin" />
						<label for="admin">Admin</label>		
						<?php cmsToolKit::mkHelp('Only admins can grant roles and permissions.'); ?>
					</fieldset>
                <?php endif; ?>
                
                <?php 
                	$i=0;		
                	foreach ( $roles->roles() as $role ):
						if( $user->canGrant( $role->getID() ) ): 
                ?>
                	<fieldset  class="role">
                		<input type="checkbox" id="<?php echo $role->getID(); ?>" class="role userRole" value="<?php echo $role->getID(); ?>" />
						<label for="<?php echo $role->getID(); ?>"><?php echo $role->label->label; ?></label>
						<input type="hidden" class="rolePermissions" value='<?php echo json_encode( $role->permissions ); ?>' />
						<input type="hidden" class="roleColor" value='<?php echo $colors[($i%$numColors)]; ?>' />
					</fieldset>
				<?php 
							$i++; 
						endif;				
					endforeach; 
				?>
            </fieldset>
        </div>        
        
        
		<div id="bottom">
         	<fieldset id="permissionsArea">
                <legend>Permissions:</legend>
                <?php $permissions->mkForm('userPermission'); ?>			
		    </fieldset>
		    <fieldset id="grantableRoles" class="hide">
		    	<legend>Grantable Roles:</legend>
		    	<?php 
                	$i=0;		
                	foreach ( $roles->roles() as $role ): 
                ?>
                	<fieldset  class="role grantable grantableRole hide">
                		<input type="checkbox" id="grantable_<?php echo $role->getID(); ?>" class="role grantableRole" value="<?php echo $role->getID(); ?>" />
						<label for="grantable_<?php echo $role->getID(); ?>"><?php echo $role->label->label; ?></label>
						<input type="hidden" class="roleColor" value='<?php echo $colors[$i]; ?>' />
					</fieldset>
					<?php $i++; ?>
				<?php endforeach ?>
		    </fieldset>
		    <fieldset id="grantablePermissions" class="hide">
		    	<legend>Grantable Permissions:</legend>
		    	<?php $permissions->mkForm('grantablePermission'); ?>	
		    </fieldset>			
        </div>
               
    </form>
    
</section>
<?php  include( ELEMENTS_PATH."/footer.php" ); ?>