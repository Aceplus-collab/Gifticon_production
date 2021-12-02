<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Reward_model extends MY_Model
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
        $this->db->select('tbl_reward.*,tbl_reward.id as rid,,tbl_reward.size as rsize,tbl_reward.country_id as cid,tbl_reward.is_active as ris_active,tbl_gifticons.*,tbl_businesses.name as bname,tbl_businesses.username as busername');
        $this->db->from('tbl_reward');
        $this->db->join('tbl_gifticons', 'tbl_reward.gifticon_id = tbl_gifticons.id');
        $this->db->join('tbl_businesses', 'tbl_reward.business_id = tbl_businesses.id'); 
        $this->db->where('tbl_reward.is_delete', '0');
        $i = 0;
        $order_by = array('tbl_reward.id' => 'desc'); // Default order by
        $search_fields = array('tbl_gifticons.name','tbl_businesses.name','tbl_businesses.username'); //search fields
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
         
        /*if(isset($_REQUEST['order'])) // here order processing
        {
            $this->db->order_by($_REQUEST['sort'], $_REQUEST['order']);
        } 
        else if(isset($order_by))
        {
            $order = $order_by;
            $this->db->order_by('tbl_reward.id', 'DESC');
        }*/
    }

    function getAllUser()
    {
        $this->_get_datatables_query();
        if($_REQUEST['limit'] != -1)
        $this->db->limit($_REQUEST['limit'], $_REQUEST['offset']);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $data = $query->result_array();
            
            foreach ($data as $key => $value) {
                $country_detail = $this->db->get_where('tbl_gift_country',array('id'=>$value['cid']))->row_array();
                $data[$key]['country_name'] = $country_detail['name'];
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    function getNzlist()
    {
        $this->db->select("tbl_gifticons.id as gifticon_id,tbl_gifticons.name as gift_name,tbl_businesses.id as business_id,tbl_businesses.name as business_name");
        $this->db->from('tbl_gifticons');
        $this->db->join('tbl_businesses',"tbl_gifticons.business_id= tbl_businesses.id ",'left');
        $this->db->join('tbl_business_country',"tbl_business_country.business_id= tbl_businesses.id ",'left');
        $this->db->where(" tbl_gifticons.is_active = 1 AND tbl_businesses.is_active = 1 AND tbl_gifticons.is_delete = 0 AND tbl_business_country.gift_country_id = '1' ");
        $this->db->group_by("tbl_gifticons.id");
        $this->db->order_by("tbl_gifticons.sequence ASC");
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            return $products = $query->result_array();
        }else{
            return array();
        }    
    }

    function getAuslist()
    {
        $this->db->select("tbl_gifticons.id as gifticon_id,tbl_gifticons.name as gift_name,tbl_businesses.id as business_id,tbl_businesses.name as business_name");
        $this->db->from('tbl_gifticons');
        $this->db->join('tbl_businesses',"tbl_gifticons.business_id= tbl_businesses.id ",'left');
        $this->db->join('tbl_business_country',"tbl_business_country.business_id= tbl_businesses.id ",'left');
        $this->db->where(" tbl_gifticons.is_active = 1 AND tbl_businesses.is_active = 1 AND tbl_gifticons.is_delete = 0 AND tbl_business_country.gift_country_id = '1' ");
        $this->db->group_by("tbl_gifticons.id");
        $this->db->order_by("tbl_gifticons.sequence ASC");
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            return $products = $query->result_array();
        }else{
            return array();
        }    
    }

}
