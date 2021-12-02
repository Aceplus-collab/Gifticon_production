<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Admin_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		
	}
	function checkLogin($data)
	{
		$query=$this->db->get_where('tbl_admin', $data);
		return $query->row_array();
	}
	
}
?>