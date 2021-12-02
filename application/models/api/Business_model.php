<?php
/**
 * This is an REST API
 * all done with a hardcoded array
*/

class Business_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->per_page = 10;
    }
    function add_user($param)
    {
    	$this->db->insert("tbl_businesses",$param);
    	return $this->db->insert_id();
    }
    function update_user($id,$params)
    {   
        return $this->db->update('tbl_businesses',$params,array("id"=>$id));
    }
    function get_user($user_id)
    {
       	$query=$this->db->get_where("tbl_businesses",array('id'=>$user_id));
    	if($query->num_rows()>=1)
    	{
    		$data=$query->row_array();
    		$data['image']=BRAND_IMAGE.$data['image'];

            return $data;
    	}
    	else
    	{
    		return array();
    	}
    }

    function get_business($user_id)
    {
        $query=$this->db->get_where("tbl_businesses",array('id'=>$user_id));
        if($query->num_rows()>=1)
        {
            $data=$query->row_array();
            $data['image']=BRAND_IMAGE.$data['image'];
            return $data;
        }
        else
        {
            return array();
        }
    }

    function getGift($user_id)
    {
        $query=$this->db->get_where("tbl_gifticons",array('id'=>$user_id));
        if($query->num_rows()>=1)
        {
            $data=$query->row_array();
            $data['image']=GIFT_IMAGE.$data['image'];
            $brand_detail = $this->get_business($data['business_id']);
            $data['brand_name'] = $brand_detail['name'];
            $data['brand_image'] = $brand_detail['image'];
            $detail = $this->db->get_where('tbl_business_country',array('business_id'=>$data['business_id']))->row_array();
            $countrydetail = $this->db->get_where('tbl_gift_country',array('id'=>$detail['gift_country_id']))->row_array();

            $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$user_id))->result_array();
            
            $data['gift_size'] = $gift_size;

            $data['country_name'] = $countrydetail['name'];
            $data['short_name'] = $countrydetail['short_code'];
            $data['flag'] = BRAND_IMAGE.$countrydetail['flag'];

            return $data;
        }
        else
        {
            return array();
        }
    }
}
?>