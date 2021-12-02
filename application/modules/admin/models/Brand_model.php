<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Brand_model extends MY_Model
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
        $this->db->from('tbl_businesses');
        $this->db->where('is_delete', '0');
        $i = 0;
        $order_by = array('name' => 'asc'); // Default order by
        $search_fields = array('name'); //search fields
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
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }

    
    function checkCategoryExists($name,$id=null){
        if($id==null)
        {
            return $this->db->get_where("tbl_businesses",array("username"=>$name))->result();
        }else{
            return $this->db->get_where("tbl_businesses",array("username"=>$name,"id !="=>$id))->result();
        }
    }

    function getPurchase($brand_id)
    {
        $this->_get_datatables_query_purchase($brand_id);
        if($_REQUEST['limit'] != -1)
        $this->db->limit($_REQUEST['limit'], $_REQUEST['offset']);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $data = array();
            foreach ($query->result_array() as $key => $value) {

                $userdata = $this->db->get_where('tbl_user',array('id'=>$value['user_id']))->row_array();
                $businessdata = $this->db->get_where('tbl_businesses',array('id'=>$value['business_id']))->row_array();
                $gifticonsdata = $this->db->get_where('tbl_gifticons',array('id'=>$value['gifticon_id']))->row_array();

                $value['username'] = $userdata['username'];

                $value['business_name'] = $businessdata['username'];

                $value['gift_name'] = $gifticonsdata['name'];
                $value['gift_image'] = $gifticonsdata['image'];

                if($value['giftto_user_id'] != 0)
                {
                    $utodata = $this->db->get_where('tbl_user',array('id'=>$value['giftto_user_id']))->row_array();
                    $value['giftto_user_name'] = $utodata['username']; 
                }else{
                    $value['giftto_user_name'] = '-';
                }

                if($value['gift_from_user_id'] != 0)
                {
                    $ufromdata = $this->db->get_where('tbl_user',array('id'=>$value['gift_from_user_id']))->row_array();
                    $value['giftfrom_user_name'] = $ufromdata['username']; 
                }else{
                    $value['giftfrom_user_name'] = '-';
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

    function count_filtered_purchase($brand_id)
    {
        $this->_get_datatables_query_purchase($brand_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query_purchase($brand_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_purchases');
        $this->db->where(array('business_id'=>$brand_id));
        $i = 0;
        $order_by = array('id' => 'DESC'); // Default order by
        $search_fields = array('business_id'); //search fields
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

    
}
