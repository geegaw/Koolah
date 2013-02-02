<?php
    $active = array('admin', 'users');
    $title = 'roles-admin';
	$css = array('userRoles', 'roles');
    $js = array('objects/types/roles', 'roles', 'permissions');
    include( ELEMENTS_PATH."/header.php" );
	
	$permissions = new Permissions($user);
	
	$active = 'roles';
	include( NAVS_PATH.'/adminOptions.php' );
	$adminNav = new MenuTYPE( $cmsMongo, 'adminNav' );
	$adminNav->read( array('menuItems'=>$adminNavBson) );
?> 

   
<section id="roles">        
    <div class="leftBlock">
        <?php $adminNav->display( $active, 'div', true ); ?>
    </div>
    
    <div class="mainBlock">
        <div id="msgBlock" class="hide"></div>
        <?php if ($user->can('c_roles') ): ?>
        <div id="addNewRole"><a href="">Add Role</a></div>
        <?php endif ?>
        <ul id="rolesList"></ul>
    </div>
    
    <form id="roleForm" class="hide">
		<fieldset id="roleNameFieldset"> 
			<label for="roleName">Name</label>
			<input type="text" name="roleName" id="roleName" class="required" placeholder="Name" value="" />
		</fieldset>
		<?php $permissions->mkForm();  ?>	
        <fieldset>
        	<input type="submit" class="cancel" id="cancel" value="Cancel" />
        	<input type="submit" class="reset" id="reset" value="Reset" />
        	<input type="submit" class="save submit" id="save" value="Save" />
        	<input type="hidden" id="roleID" value="" />
        </fieldset>
	</form>
   	
</section>

<?php include( ELEMENTS_PATH."/footer.php" ); ?>