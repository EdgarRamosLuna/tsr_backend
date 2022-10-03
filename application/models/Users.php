<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Users extends MY_Model 
{   
    public function __construct(){
		parent::__construct();
		$this->table_name = 'usuarios_admin2';
	}
    public function getData()
    {
        $query = $this->db->get('usuarios_admin2');
        return $query->result();
    }                        
                        
}