<?php

class RolesTYPE extends Nodes
{
    //CONSTRUCT	
    public function __construct( $db  ){
    	parent::__construct( $db, ROLES_COLLECTION );	
    }
    
	/***
	 * BOOLS
	 */
	public function can( $permission ){
		if ( $this->isNotEmpty() ){
			foreach( $this->roles() as $role ){
				if ( $role->can( $permission ) )
					return true;
			}
		}
		return false;
	}
	/***/
	
    //GETTERS
	public function roles(){ return $this->nodes; }
	
	
	//FETCHERS
	public function get( $q=null, $fields=null, $orderBy=array('label'=>1), $distinct=null  ){
		$bsonArray = parent::get( $q, $fields,  $orderBy);
		//debug::printr($bsonArray);
		if ( count($bsonArray) ){
			foreach ( $bsonArray as $bson ){
				$role = new RoleTYPE( $this->db, $this->collection );
				$role->read( $bson );
				$this->append( $role );
			}
		}	
	}
	
	/***
	 * MONGO FUNCTIONS
	 */
	public function prepare(){
		return array( 'roles'=>parent::prepare() );
	}
	
	/*
	public function read( $bson ){
		if ( $bson && isset($bson['roles']) ){
			$this->clear();			
			foreach ( $bson['roles'] as $node ){
				$role = new RoleTYPE( $this->db, $this->collection );
				$role->read( $bson );
				$this->append( $role );
			}				
		}						
	}
	 * */
	/***/ 
			   
} 
/*
class RolesTYPE
{
    public $roles;
    public $numRoles;
    
    private $dbc;
    private $table;

    public function __construct($dbc=null, $table='role')
    {
        $this->dbc = $dbc;
        $this->table = $table;
        
        $this->roles = null;
        $this->numRoles = 0;
    }
    
    public function getAll()
    {
        if ($results = $this->dbc->find( $this->table, '*', null, 'label' ) )
        {
            foreach( $results as $result )
            {
                $id = (int) $result->id;
                if (!preg_match('/custom/i', $result->name))
                {
                    $role = new RoleTYPE($this->dbc);
                    $role->getByID( $id );
                    $this->roles[]=$role;
                    $this->numRoles++;
                } 
            }
        }
    }
    
    public function set( $roles )
    {
        $this->clear();
        if ( count($roles) )
        {
            foreach ( $roles as $role )
                $this->append( $role );        
        } 
    }
    
    public function clear()
    {
        $this->roles = null;
        $this->numRoles = 0;
    }
    
    public function append( $role )
    {
        if ( $role )
        {
            if ( is_numeric($role) )
            {
                $newRole = new RoleTYPE( $this->dbc );
                $newRole->getByID( $role );
                $role = $newRole;                            
            }                            
            $this->roles[] = $role;
            $this->numRoles++;
        }    
    }
    
    public function hasPermission( $permission )
    {
        if ( $this->numRoles )
        {
            foreach ( $this->roles as $role )
            {
                if ( $role->hasPermission($permission) )
                    return true; 
            }
        }
        return false;
    }
   
    public function makeRolesCheckBoxes()
    {
        $checkBoxes = null;
        if ($this->numRoles)
        {
            foreach( $this->roles as $role)
            {
                $checkBox = new CheckboxInputTYPE();
                    $checkBox->multiple = true;
                    $checkBox->name = "roles";
                    $checkBox->id = "roles-".$role->getName();
                    $checkBox->value = json_encode(array('role_id'=>$role->getID(), 'perms'=>$role->rolePermissions->JSONpermissionsIDs()));
                    $checkBox->html_class="role ".$role->getName();
                    $checkBox->label = $role->label;
                    $checkBox->fieldset = true;
                    $checkBox->fieldsetClass = 'roleCheckBox';                                        
                $checkBoxes[] = $checkBox;
            }
        }
        return $checkBoxes;
    }
    
    public function JSONRoles()
    {
       $encode = null;
       $customEncode = null;
       if ($this->numRoles)
       {
            foreach ($this->roles as $role)
            {
                if ( $role->label == 'custom' )
                    $custom = $role;
                else
                    $encode[] = $role->getID();
            }   
            
            if ( isset($custom) )
            {
                if ( $custom->rolePermissions->numPermissions )
                {
                    foreach ( $custom->rolePermissions->permissions as $perm )
                        $customEncode[] = $perm->getID();    
                }
            }
       } 
       return json_encode( array( 'roles'=>$encode, 'custom'=>$customEncode ) );
    }
} 


    /*
    public function getUserRoles($user_id)
    {
        if ($results = $this->dbc->find( $this->table, '*', "user_id=$user_id") )
        {
            foreach ($results as $result)
            {
                $id = (int) $result->role_id;
                $role = new RoleTYPE($this->dbc);
                $role->findByID( $id );
                $this->roles[]=$role;
            }
            $this->numRoles = count($this->roles);
        }
    }
    
    public function updateUserRoles( $user_id )
    {
        if ( $this->delUserRoles($user_id) )
        {
            if ( $this->roles )
            {
                foreach( $this->roles as $role )
                {
                    if ( $role->label != 'custom' )
                    {
                        if (!$this->dbc->insert('roles_users', 'role_id, user_id', $role->getID().", $user_id" ))
                            return false;
                    }
                }
            }            
        }
        else
            return false;
        return true;
    }
    
    public function delUserRoles($user_id)
    {
        
        $user = new UserTYPE($this->dbc);
        $user->findByID($user_id);
        
        if ( $user->roles )
        {
            if ($customRole = $user->getCustomRole())
            {
                $where = "user_id = $user_id AND role_id != ".$customRole->getID();
                return $this->dbc->delete('roles_users', $where);
            }
            else
                return false;
        }
        return true;
    }
    */


?>
