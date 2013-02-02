<?php

class RolesPermissionsTYPE
{
    public $roles;
    public $numRoles;

    private $RolesPermissionsTable;
    private $rolesTable;
    private $permmissionsTable;
    private $dbc;
    
    public function __construct($dbc=null, $RolesPermissionsTable="roles_permissoins", $rolesTable = 'role', $permissionsTable = 'permission')
    {
        $this->RolesPermissionsTable = $RolesPermissionsTable;
        $this->rolesTable = $rolesTable;
        $this->permissionsTable = $permissionsTable;
        $this->dbc = $dbc;
        
        $this->roles = null;
        $this->numRoles = 0;
    }
    
    public function getAll()
    {
         if ( $results = $this->dbc->find( $this->rolesTable, 'id' ) )
         {
              foreach ( $results as $result )
              {
                  $role = new RoleTYPE( $this->dbc );
                  $role->findByID( $result->id );
                  $this->roles[$result->id]= $role;
                  $this->numRoles++;
                  
                  $this->getRolesPerms( $result->id );
              }
         }
    }
    
    public function getRolesPerms( $role_id )
    {
        if ($results = $this->dbc->find( $this->RolesPermissionsTable, '*', "role_id = $role_id" ) )
        {
            foreach( $results as $result )
                $this[ $role_id ]->permissions->addPerm( $reult->permission_id );
        }
        
    }
    
    public function getPermsRoles( $perm_id )
    {
        $perm_id = (int)$perm_id;
        $query = "permission_id = $perm_id";
        if ($results = $this->dbc->find( $this->RolesPermissionsTable, '*', $query ) )
        {
            $perm = new PermissionTYPE( $this->dbc );
            $perm->findByID( $perm_id );
            foreach( $results as $result )
            {
                $role = new RoleTYPE( $this->dbc );
                $role = $role->findByID( $role_id );
                $this->roles[$role_id]= $role;
            }            
        }
    }
    
    public function updateNumRoles(){ $this->numRoles= count($this->roles); }
    
    public function setRolesPermissions( $role_id, $permissions )
    {
        if ( !isset($this->roles[$role_id] ) )
            return -1;
        $this->roles[$role_id]->permissions->set( $permissions );
    }
    
    
    public function save( $role_id = null)
    {
        if ( $role_id )
        {
            $roles = null;
            $roles[$role_id][] = $this->roles[$role_id];     
        }
        else
            $roles = $this->roles;
        
        foreach ( $roles as $role_id => $role )
        {
            if ( !$role->save() )
                return false;
                
            $this->delete( $role_id );
            foreach ( $role->permissions as $perm )
            { 
                $perm_id = $perm->getID();
                if ( !$this->dbc->insert( $this->table, "role_id, permission_id", "$role_id, $perm_id" ) )
                    return false;
            }
        }
        return true;
    }
    
    public function delete ( $role_id )
    {
        return $this->dbc->delete( $this->table, "role_id = $role_id" );
    }
    
}

?>
