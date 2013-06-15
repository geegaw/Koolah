<?php
    $active = array('admin', 'users');
    $title = 'roles-admin';
	$css = array('users');
    $js = array('objects/types/roles', 'fe/roles', 'fe/permissions');
    include( ELEMENTS_PATH."/header.php" );
	
	$permissions = new Permissions($user);
	
	$active = 'roles';
	include( NAVS_PATH.'/adminOptions.php' );
	$adminNav = new MenuTYPE( $cmsMongo, 'adminNav' );
	$adminNav->read( array('menuItems'=>$adminNavBson) );
?> 

   
<section id="roles">        
    <section id="adminNav"  class="collapsible"> 
        <div class="commandBar fullWidth"><h3>User Nav</h3><button type="button" class="toggle open">&#8211;</button></div>
        <nav  id="adminNavSectionBody" class="collapsibleBody">
            <?php $adminNav->display( $active, 'div', true ); ?>
         </nav> 
    </section>
    
    <section id="mainBlock"   class="collapsible">
        <div class="commandBar fullWidth"><h3>Roles</h3><button type="button" class="toggle open">&#8211;</button></div>
        <div  id="mainBlockSectionBody" class="collapsibleBody">
            <div class="heading fullWidth">
                <h2>Roles</h2>
                <?php if ($user->can('c_roles') ): ?><button id="addNewRole"> + </button><?php endif ?> 
            </div>
            <div id="rolesList"  class="list"><ul></ul></div>
            <div id="msgBlock" class="hide"></div>
         </div>
    </section>
    
    <form id="roleForm" class="hide">
        <div id="roleFormLeft" class="leftCol">
            <legend>Role</legend>
    		<fieldset id="roleNameFieldset"> 
    			<label for="roleName">Name</label>
    			<input type="text" name="roleName" id="roleName" class="required" placeholder="Name" value="" />
    		</fieldset>
            <fieldset  id="saveCancel">
            	<input type="submit" class="cancel noreset" id="cancel" value="Cancel" />
            	<input type="submit" class="reset noreset" id="reset" value="Reset" />
            	<input type="submit" class="save submit noreset" id="save" value="Save" />
            	<input type="hidden" id="roleID" value="" />                
            </fieldset>
        </div>
        <div id="roleFormRight" class="rightCol">
            <fieldset id="permissionsArea">
                <legend>Permissions:</legend>
                <?php $permissions->mkForm(); ?>            
            </fieldset>
        </div>
	</form>
   	
</section>

<?php include( ELEMENTS_PATH."/footer.php" ); ?>