<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Purchase_model extends MY_Model
{
    
    function __construct()
    {
        parent::__construct();
    }
    function getPurchase()
    {
        $this->_get_datatables_query_purchase();
        if($_REQUEST['limit'] != -1)
        $this->db->limit($_REQUEST['limit'], $_REQUEST['offset']);
        $query = $this->db->get();
        // $exchange_rate = $this->db->get_where('tbl_exchange_rate',array('ex_country'=>'KRW'))->row_array();
        if($query->num_rows() > 0)
        {
            $data = array();
            foreach ($query->result_array() as $key => $value) {
                $userdata = $this->db->get_where('tbl_user',array('id'=>$value['user_id']))->row_array();
                $businessdata = $this->db->get_where('tbl_businesses',array('id'=>$value['business_id']))->row_array();
                $gifticonsdata = $this->db->get_where('tbl_gifticons',array('id'=>$value['gifticon_id']))->row_array();

                $gifticonsCountryRelation = $this->db->get_where('tbl_business_country',array('business_id'=>$value['business_id']))->result_array();
                $countryList = array();
                if($gifticonsCountryRelation)
                {
                    foreach ($gifticonsCountryRelation as $key => $v) {
                        $country = $this->db->get_where('tbl_gift_country',array('id'=>$v['gift_country_id']))->row_array();
                        $countryList[] = $country['name'];
                    }   
                }
                $value['username'] = $userdata['username'];
                $value['country'] = implode(", ",$countryList);
                $value['purchase_id'] = $value['purchase_id'];

                $value['business_name'] = $businessdata['username'];

                $value['gift_name'] = $gifticonsdata['name'];
                $value['gift_image'] = $gifticonsdata['image'];
                $value['wincube_id'] = $gifticonsdata['wincube_id'];
                $value['voucher_status'] = $value['voucher_status'];
                
                //check korea item or not
                // if($gifticonsdata['wincube_id'] != null)
                // {
                //     $ex_rate = number_format((float)($value['price'] / $exchange_rate['rate']), 2, '.', '');
                //     $value['price'] = $ex_rate;
                // }else{
                //     $value['price'] = $value['price'];
                // }

                if($value['giftto_user_id'] != 0)
                {
                    $utodata = $this->db->get_where('tbl_user',array('id'=>$value['giftto_user_id']))->row_array();
                    if($utodata)
                    {
                        $value['giftto_user_name'] = $utodata['username'];
                    }else{
                        $value['giftto_user_name'] = '-';
                    }
                }else{
                    $value['giftto_user_name'] = '-';
                }

                if($value['gift_from_user_id'] != 0)
                {
                    $ufromdata = $this->db->get_where('tbl_user',array('id'=>$value['gift_from_user_id']))->row_array();
                    if($ufromdata)
                    {
                        $value['giftfrom_user_name'] = $ufromdata['username'];
                    }else{
                        $value['giftfrom_user_name'] = '-';
                    }
                }else{
                    $value['giftfrom_user_name'] = '-';
                };
                $data[] = $value;
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    function count_filtered_purchase()
    {
        $this->_get_datatables_query_purchase();
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query_purchase()
    {
        $this->db->select('*, purchase.id as purchase_id', false);
        $this->db->from('tbl_purchases as purchase');
        $i = 0;
        $order_by = array('id' => 'desc'); // Default order by
        $search_fields = array('tb_user.username'); //search fields

        //date filter
        if(isset($_REQUEST['fromDate']) || isset($_REQUEST['toDate']))
        {
            if(isset($_REQUEST['fromDate']))
            {
                $this->db->where('purchase.purchase_date >= ', date('Y-m-d', strtotime($_REQUEST['fromDate'])));
            }else if(!isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate']))
            {
                $this->db->where('purchase.purchase_date <= ', date('Y-m-d', strtotime($_REQUEST['toDate'])));
            }
            if(isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate']))
            {
                $this->db->where('purchase.purchase_date BETWEEN "'. date('Y-m-d', strtotime($_REQUEST['fromDate'])). '" and "'. date('Y-m-d', strtotime($_REQUEST['toDate'])).'"');
            }
        }

        if(isset($_REQUEST['country']))
        {
            $this->db->join('tbl_businesses as tb_businesses', 'tb_businesses.id = purchase.business_id');
            $this->db->join('tbl_business_country as tb_business_country', 'tb_business_country.business_id = purchase.business_id');
            $this->db->where(array('tb_business_country.gift_country_id'=>$_REQUEST['country']));
        }

        foreach ($search_fields as $item) // loop column 
        {
            if(isset($_REQUEST['search'])) // if datatable send POST for search
            {
                $this->db->join('tbl_user as tb_user', 'tb_user.id = purchase.user_id', 'inner');
                if($i===0) // first loop
                {
                    // $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. 
                    $this->db->like($item, $_REQUEST['search']);
                }
                else
                {
                    $this->db->or_like($item, $_REQUEST['search']);
                }
 
                // if(count($search_fields) - 1 == $i) //last loop
                    // $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_REQUEST['order'])) // here order processing
        {
            $this->db->order_by('purchase.'.$_REQUEST['sort'], $_REQUEST['order']);
        }
        else if(isset($order_by))
        {
            $order = $order_by;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}
