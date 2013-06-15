<?php
/**
 * customMySQL
 * 
 * @ignore
 * @license http://opensource.org/licenses/GPL-3.0
 * @copyright Copyright (c) 2013 Christophe Vaugeois
 */
/**
 * customMySQL
 * 
 * class that initilizes db connection 
 * @author Christophe Vaugeois <cvaugeois@koolah.org>
 * @package koolah\system\core 
 * @ignore
 */ 
class customMySQL
{
    private $dbc;
    
    private $db_user;
    private $db_password;
    private $db_host;
    private $db_name;
    
    public function __construct($db_user, $db_password, $db_host, $db_name)
    {
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_password = $db_password;
        $this->db_name = $db_name;
        $this->connect();
    }
    
    private function connect()
    {
        $this->dbc = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
        if ( ( $this->dbc->connect_errno) )
        {
            exit;   
        }
        else
        {
            $this->dbc->select_db( $this->db_name); 
            $this->dbc->set_charset ('utf8');
        } 
    }
    
    public function disconnect(){ $this->dbc->close(); }
    
    public function real_escape_string ( $s ) { return $this->dbc->real_escape_string($s); }
    
    public function find($from, $field, $where=null, $orderBy=null, $limit=null)
    {
        $tmp = null;
        
        $from = $this->dbc->real_escape_string($from);
        $field = $this->dbc->real_escape_string($field);
        $query = "SELECT $field FROM $from";
        
        if ($where)
        {
            //$where = $this->dbc->real_escape_string($where);
            $query .= " WHERE $where";
        }
        if ($orderBy)
        {
            $orderBy = $this->dbc->real_escape_string($orderBy);
            $query .= " ORDER BY $orderBy";
        }  
        if ($limit)
        {
            $limit = $this->dbc->real_escape_string($limit);
            $query .= " LIMIT $limit";
        }
        
        //echo $query.'<br />';
        if ( $result = $this->dbc->query($query) )
        {
            //$fields = explode(', ', $field);
            while ( $obj = $result->fetch_object() )
                $tmp[] = $obj;        
        } 
        
        return $tmp;         
    }
    
    function insert ( $table, $cols, $values )
    {
        $query = "INSERT INTO $table ($cols) VALUES ($values)";
        //echo $query.'<br />';;
        if ($this->dbc->query($query))
            return $this->dbc->insert_id;
        else
            return false;
    }
    
    function update( $table, $set, $where, $limit=null)
    {
        $query = "UPDATE $table SET $set WHERE $where";
        if ($limit)
            $query .=" LIMIT $limit";
        //echo $query.'<br />';;
        return $this->dbc->query($query);
    }
    
    function delete( $table, $where, $limit=null)
    {
        $query = "DELETE FROM $table WHERE $where";
        if ($limit)
            $query .=" LIMIT $limit";
        //echo $query.'<br />';
        return $this->dbc->query($query);
    }

}

?>
