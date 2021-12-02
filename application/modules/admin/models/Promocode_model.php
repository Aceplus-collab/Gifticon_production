<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Promocode_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
	private function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->from('tbl_coupon_codes');
        $i = 0;
        $order_by = array('id' => 'desc'); // Default order by
        $search_fields = array('code'); //search fields
        foreach ($search_fields as $item) // loop column 
        {
            if(isset($_REQUEST['search'])) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. 
                    $this->db->like($item, $_REQUEST['search']);
                }
                else
                {
                    $this->db->or_like($item, $_REQUEST['search']);
                }
 
                if(count($search_fields) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_REQUEST['order'])) // here order processing
        {
            $this->db->order_by($_REQUEST['sort'], $_REQUEST['order']);
        } 
        else if(isset($order_by))
        {
            $order = $order_by;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

	function getAllUser()
	{
		$this->_get_datatables_query();
        if($_REQUEST['limit'] != -1)
        $this->db->limit($_REQUEST['limit'], $_REQUEST['offset']);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $data = array();

            foreach ($query->result_array() as $key => $value) {
                $countrows = $this->db->get_where('tbl_purchases',array('coupon_code'=>$value['code']))->num_rows();
                if($countrows != null)
                {
                    $value['total_used'] = $countrows;
                }else{
                    $value['total_used'] = 0;
                }
                $data[] = $value;
            }
            return $data;
        }
        else
        {
            return false;
        }
    }   

}


?>