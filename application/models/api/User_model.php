<?php
/**
 * This is an REST API
 * all done with a hardcoded array
*/

class User_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->per_page = 10;
    }
    function add_user($param)
    {
    	$this->db->insert("tbl_user",$param);
    	return $this->db->insert_id();
    }
    function update_user($id,$params)
    {   
        return $this->db->update('tbl_user',$params,array("id"=>$id));
    }
    function get_user($user_id)
    {
       	$query=$this->db->get_where("tbl_user",array('id'=>$user_id));
    	if($query->num_rows()>=1)
    	{
    		$data=$query->row_array();
    		$data['profile_image']=PROFILE_IMAGE.$data['profile_image'];

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
            $detail = $this->db->get_where('tbl_business_country',array('business_id'=>$data['business_id']))->row_array(); //
           // print_r($detail); 
            $countrydetail = $this->db->get_where('tbl_gift_country',array('id'=>$detail['gift_country_id']))->row_array();

            $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$user_id))->result_array();
            
            $data['gift_size'] = $gift_size;
            $data['country_name'] = $countrydetail['name'];
            $data['short_name'] = $countrydetail['short_code'];
            $data['flag'] = BRAND_IMAGE.$countrydetail['flag'];

            if($data['gifticon_type'] == 1)
            {
                $checkdata = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$user_id,'is_used'=>0))->row_array();
                if($checkdata)
                {
                   $data['plain_code'] = $checkdata['code'];
                   $data['pin'] = $checkdata['notes']; 
                   $data['giftcard_id'] = $checkdata['id'];
                }
            }

            $data['qry_remaining'] = $this->qtyRemaing($user_id);

            return $data;
        }
        else
        {
            return (object) array();
        }
    }

    function getGiftReward($user_id)
    {
        $query=$this->db->get_where("tbl_gifticons",array('id'=>$user_id));
        if($query->num_rows()>=1)
        {

            $data=$query->row_array();
            $data['image']=GIFT_IMAGE.$data['image'];
            $brand_detail = $this->get_business($data['business_id']);
            $data['brand_name'] = $brand_detail['name'];
            $data['brand_image'] = $brand_detail['image'];
            $detail = $this->db->get_where('tbl_business_country',array('business_id'=>$data['business_id']))->row_array(); //
           // print_r($detail); 
            $countrydetail = $this->db->get_where('tbl_gift_country',array('id'=>$detail['gift_country_id']))->row_array();

            $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$user_id))->result_array();
            
            $data['gift_size'] = $gift_size;
            $data['country_name'] = $countrydetail['name'];
            $data['short_name'] = $countrydetail['short_code'];
            $data['flag'] = BRAND_IMAGE.$countrydetail['flag'];

            if($data['gifticon_type'] == 1)
            {
                $checkdata = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$user_id))->row_array();
                if($checkdata)
                {
                   $data['plain_code'] = $checkdata['code'];
                   $data['pin'] = $checkdata['notes']; 
                   $data['giftcard_id'] = $checkdata['id'];
                }
            }

            $data['qry_remaining'] = $this->qtyRemaing($user_id);

            return $data;
        }
        else
        {
            return (object) array();
        }
    }

    function purchasedetail($user_id,$purchase_id)
    {
        $purchaseddata = $this->db->get_where('tbl_purchases',array('id'=>$purchase_id))->row_array();
        if($purchaseddata)
        {
                $purchaseddata['giftdetails'] = $this->User_model->getGift($purchaseddata['gifticon_id']);

                if($user_id == $purchaseddata['user_id'])
                {
                    $purchaseddata['is_purchased'] = '1';
                }else{
                    $purchaseddata['is_purchased'] = '0';
                }

                if($purchaseddata['gift_from_user_id'] != 0)
                {
                    $userdetail = $this->User_model->get_user($purchaseddata['gift_from_user_id']);
                    $purchaseddata['sent_from'] = $userdetail['username'];
                }else{
                    $purchaseddata['sent_from'] = '';
                }

                if($purchaseddata['giftto_user_id'] != 0)
                {
                    $userdetail = $this->User_model->get_user($purchaseddata['giftto_user_id']);
                    $purchaseddata['sent_to'] = $userdetail['username'];
                }else{
                    $purchaseddata['sent_to'] = '';
                }

                $transaction = $this->db->get_where('tbl_transaction',array('stripe_charge_token'=>$purchaseddata['stripe_charge_token']))->row_array();

                if(isset($transaction['metadata']) && $transaction['metadata'] != "")
                {
                    $mdata = json_decode($transaction['metadata'],true);

                    foreach ($mdata as $mkey => $mvalue) {
                        $gidtl = $this->db->get_where('tbl_gifticons',array('id'=>$mvalue['gift_id']))->row_array();
                        $bdtl = $this->db->get_where('tbl_businesses',array('id'=>$gidtl['business_id']))->row_array();
                        $mdata[$mkey]['giticon_name'] = $gidtl['name']; 
                        $mdata[$mkey]['brand_name'] = $bdtl['name']; 
                    }

                    $transaction['metadata'] = $mdata; 
                }

                $purchaseddata['transaction'] = $transaction;

           return $purchaseddata;

        }else{
            return array();
        }
    }

    function qtyRemaing($gifticon_id)
    {
        $giftcheck = $this->db->get_where('tbl_gifticons',array('id'=>$gifticon_id))->row_array();
        if($giftcheck['gifticon_type'] == 1)
        {
            $cnt = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$gifticon_id,'is_used'=>0))->num_rows();
            if($cnt && $cnt != NULL)
            {
                return $cnt;
            }else
            {
                return 0;
            }    
        }else{
            return '';
        }
    }

    function getMydata($user_id,$page)
    {
        $purchase = array();
        $purchase2 = array();

        $this->db->select("tbl_purchases.*,tbl_purchases.id as purchase_id");
        $this->db->from('tbl_purchases');
        $this->db->where(" ((user_id = '".$user_id."' AND `is_redeem` = 0 AND purchaser = 'mygiftbox') OR (`giftto_user_id` = '".$user_id."' AND `is_redeem` = 0)) AND (purchaser = 'mygiftbox' OR receiver = 'mygiftbox') ORDER BY last_update desc ");    // AND (move_to IS NOT NULL OR move_to = 'mygitbox' )
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {   
            $purchase = $query->result_array();
        }

        $this->db->select("tbl_purchases.*,tbl_purchases.id as purchase_id");
        $this->db->from('tbl_purchases');
        $this->db->where(" ((user_id = '".$user_id."' AND `is_redeem` = 0 AND move_to = 'mygiftbox') OR (`giftto_user_id` = '".$user_id."' AND `is_redeem` = 0 AND move_to = 'mygiftbox' )) ORDER BY last_update desc ");    
        $query2 = $this->db->get();
        if($query2->num_rows() >= 1)
        {
            $purchase2 = $query2->result_array();
        }

        $merger_array = array_merge($purchase,$purchase2);
        /*print_r($merger_array);
        die;
        $uniqucarray = array_unique($merger_array);

        print_r($uniqucarray);*/

        $pdata = $merger_array; //$this->array_sort_by_column($merger_array,'last_update');

        if($pdata)
        {
            foreach ($pdata as $key => $value) {
                $purchase[$key]['gifticons'] = $this->User_model->getGift($value['gifticon_id']);

                if($user_id == $value['user_id'])
                {
                    $purchase[$key]['is_purchased'] = '1';
                }else{
                    $purchase[$key]['is_purchased'] = '0';
                }

                if($value['gift_from_user_id'] != 0)
                {
                    $userdetail = $this->User_model->get_user($value['gift_from_user_id']);
                    $purchase[$key]['sent_from'] = $userdetail['username'];
                }else{
                    $purchase[$key]['sent_from'] = '';
                }

                if($value['giftto_user_id'] != 0)
                {
                    $userdetail1 = $this->User_model->get_user($value['giftto_user_id']);
                    $purchase[$key]['sent_to'] = $userdetail1['username'];
                }else{
                    $purchase[$key]['sent_to'] = '';
                }

                if($value['main_image'] != '')
                {
                    $purchase[$key]['main_image'] = GIFT_IMAGE.$value['main_image'];
                }

                if($value['crop_image'] != '')
                {
                    $purchase[$key]['crop_image'] = GIFT_IMAGE.$value['crop_image'];
                }    
            }    
            return $purchase;
       }else{
            return array();
       }    
    }

    function array_sort_by_column(&$array, $column, $direction = SORT_DESC) {
        $reference_array = array();

        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        return array_multisort($reference_array, $direction, $array);
    }

    function mygiftNOTIN($user_id)
    {
        $this->db->select("tbl_purchases.*,tbl_purchases.id as purchase_id");
        $this->db->from('tbl_purchases');
        $this->db->where(" ((user_id = '".$user_id."' OR `giftto_user_id` = '".$user_id."') AND `is_redeem` = 0 AND gift_from_user_id != '".$user_id."' ) OR (id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'mygiftbox') )  ORDER BY last_update desc");    
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $pid = array();
            $purchase = $query->result_array();
            foreach ($purchase as $key => $value) {
                $pid[] = $value['purchase_id'];
            }

            $pids = implode(',', $pid);
            return $pids;
        }else{
            return '';
        }
    }

    function usedsendNOTIN($user_id)
    {
        $this->db->select("tbl_purchases.*,tbl_purchases.id as purchase_id");
        $this->db->from('tbl_purchases'); 
        
        $this->db->where(" ( (user_id = '".$user_id."' AND `is_redeem` = 1) OR (`giftto_user_id` = '".$user_id."' AND `is_redeem` = 1 ) OR ( `gift_from_user_id` = '".$user_id."' ) ) OR ( id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'used_sent') )  ORDER BY last_update desc ");

        $query = $this->db->get();
        if($query->num_rows() >= 1) 
        {
            $pid = array();
            $purchase = $query->result_array();
            foreach ($purchase as $key => $value) {
                $pid[] = $value['purchase_id'];
            }

            $pids = implode(',', $pid);
            return $pids;
        }else{
            return '';
        }    
    }
        
}
?>