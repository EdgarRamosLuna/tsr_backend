<?php


class MY_Model extends CI_Model{

	public $table_name;

	public function __construct(){
		parent::__construct();;
	}//fin del construct

	public function findByField($field, $data){
		$query = $this->db->where($field, $data)->get($this->table_name);
		return $query;
	}//fin de findByField

	public function deleteByField($field, $data){
		$query = $this->db->where($field, $data)->delete($this->table_name);
		return $query;
	}

	public function findAll($ord_by = 'id', $ord_tip = 'asc'){
		$query = $this->db->order_by($ord_by, $ord_tip);
		$query = $this->db->get($this->table_name);
		return $query;
	}//Final de getAll

	public function insertData($data){
		if($this->db->insert($this->table_name, $data)){
			return true;
		}else{
			return false;
		}
	}//fin de insertData

	public function updateByField($field_name, $field_data, $data_array){
		if($this->db->where($field_name, $field_data)->update($this->table_name, $data_array)){
			return true;
		}else{
			return false;
		}	
	}//fin de updateByField

}//fin de MY_Model