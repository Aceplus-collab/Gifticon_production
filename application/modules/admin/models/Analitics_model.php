<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Analitics_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	
   function getAnalitics($searchdate)
   {
        $title_0 = "Total purchase made in a day";
        //$value_0 = $this->db->get_where('tbl_purchases',array('date(last_update)'=>$searchdate))->num_rows();
        $value_0 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) = '=>$searchdate,'type'=>'purchased_page'))->num_rows();
       
        $title_1 = "Total QR Code redeems in a day";
        $value_1 = $this->db->get_where('tbl_purchases',array('date(CONVERT_TZ(last_update, "UTC", "NZ")) = '=>$searchdate,'gifticon_type'=>0,'is_redeem'=>1))->num_rows();

        $title_2 = "Total signup completed in a day";
        $value_2 = $this->db->get_where('tbl_user',array('date(CONVERT_TZ(inertdate, "UTC", "NZ")) = '=>$searchdate,'step'=>'done'))->num_rows();

        $title_3 = "Total users clicked on reward function in a day";
        $value_3 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'reward_page'))->num_rows();

        $title_4 = "Total users who clicked on friends list in a day";
        $value_4 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'friend_page'))->num_rows();

        $title_5 = "Total users who clicked on notification in a day";
        $value_5 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'notification_page'))->num_rows();

        $title_6 = "Total users who clicked on settings in a day";
        $value_6 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'setting_page'))->num_rows();

        $title_7 = "Total users who has set language as English";
        $value_7 = $this->db->get_where('tbl_user',array('language'=>'0'))->num_rows();

        $title_8 = "Total users who has set language as Korean";
        $value_8 = $this->db->get_where('tbl_user',array('language'=>'1'))->num_rows();

        $title_9 = "Total users who has set language as Japanese";
        $value_9 = $this->db->get_where('tbl_user',array('language'=>'3'))->num_rows();

        $title_10 = "Total users who has set language as Chinese";
        $value_10 = $this->db->get_where('tbl_user',array('language'=>'2'))->num_rows();

        $title_11 = "Total users who clicked on Giftshop icon from home page + navigation bar in a day";
        $value_11 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'giftshop_page'))->num_rows();

        $title_12 = "Record of users keywork typed into the search bar in a day";
        $value_12 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'search_page'))->num_rows();

        $title_13 = "Total users who clicked on any item in a day";
        $value_13 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'product_page'))->num_rows();

        $title_14 = "Total users who clicked on ‘Buy & Gift button’ in a day";
        $value_14 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'checkout_page'))->num_rows();

        $title_15 = "Total users who successfully applied discount codes in a day";
        $value_15 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'applydiscount_page'))->num_rows();

        $title_16 = "Total users who clicked ‘Continue to payment’ inside My cart in a day";
        $value_16 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'payment_page'))->num_rows();

        $title_17 = "Total users who has clicked on ‘Add payment method’ button in a day";
        $value_17 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'addpayment_page'))->num_rows();

        $title_18 = "Total users who has clicked ‘Gift this’ button from their My Giftbox page in a day";
        $value_18 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_page'))->num_rows();

        $title_19 = "Total users who clicked ‘Gifticon’ button in the gift using page in a day";
        $value_19 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_gifticon_page'))->num_rows();

        $title_20 = "Total users who clicked ‘Facebook’ button in the gift using page in a day";
        $value_20 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_facebook_page'))->num_rows();

        $title_21 = "Total users who clicked ‘Instagram’ button in the gift using page in a day";
        $value_21 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_insagram_page'))->num_rows();

        $title_22 = "Total users who clicked ‘Wechat’ button in the gift using page in a day";
        $value_22 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_wechat_page'))->num_rows();

        $title_23 = "Total users who clicked ‘Kakaotalk’ button in the gift using page in a day";
        $value_23 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_kakaotalk_page'))->num_rows();

        $title_24 = "Total users who clicked ‘Whatsapp’ button in the gift using page in a day";
        $value_24 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_whatsapp_page'))->num_rows();

        $title_25 = "Total users who clicked ‘Others’ button in the gift using page in a day";
        $value_25 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_other_page'))->num_rows();

        $title_26 = "Total users who completed successfully sending to NZ mobile number in a day";
        $value_26 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_nz_number_page'))->num_rows();

        $title_27 = "Total users who completed successfully sending to AUS mobile number in a day";
        $value_27 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_aus_number_page'))->num_rows();

        $title_28 = "Total users who completed successfully sending to KR mobile number in a day";
        $value_28 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_kr_number_page'))->num_rows();

        $title_29 = "Total users who clicked on the ‘GIF’ icon in the decoration page in a day";
        $value_29 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gif_page'))->num_rows();

        $title_30 = "Total users who clicked on the ‘pen tool’ in the decoration page in a day";
        $value_30 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'pentool_page'))->num_rows();

        $title_31 = "Total users who clicked on any colour to change colour of card in a day";
        $value_31 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'cardcolor_page'))->num_rows();

        $title_32 = "Total users who started typing to write message in the decoration page in a day";
        $value_32 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'writemessage_page'))->num_rows();

        $title_33 = "Total users who has change the colour of the text they wrote in a day";
        $value_33 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'textbackground_page'))->num_rows();

        $title_34 = "Total gift made in a day";
        $value_34 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'gift_friend_page'))->num_rows();

        $title_35 = "Number of total users who has opened the app within a day";
        $value_35 = $this->db->get_where('tbl_analitics_logs',array('date(CONVERT_TZ(updatetime, "UTC", "NZ")) ='=>$searchdate,'type'=>'visitors_page','macaddress ='=>''))->num_rows();

        $title = Array (
                0 => $title_0,
                1 => $title_1,
                2 => $title_2,
                3 => $title_3,
                4 => $title_4,
                5 => $title_5,
                6 => $title_6,
                7 => $title_7,
                8 => $title_8,
                9 => $title_9,
                10 => $title_10,
                11 => $title_11,
                12 => $title_12,
                13 => $title_13,
                14 => $title_14,
                15 => $title_15,
                16 => $title_16,
                17 => $title_17,
                18 => $title_18,
                19 => $title_19,
                20 => $title_20,
                21 => $title_21,
                22 => $title_22,
                23 => $title_23,
                24 => $title_24,
                25 => $title_25,
                26 => $title_26,
                27 => $title_27,
                28 => $title_28,
                29 => $title_29,
                30 => $title_30,
                31 => $title_31,
                32 => $title_32,
                33 => $title_33,
                34 => $title_34,
                35 => $title_35,
        );

        $valuedata = Array (
                0 => $value_0,
                1 => $value_1,
                2 => $value_2,
                3 => $value_3,
                4 => $value_4,
                5 => $value_5,
                6 => $value_6,
                7 => $value_7,
                8 => $value_8,
                9 => $value_9,
                10 => $value_10,
                11 => $value_11,
                12 => $value_12,
                13 => $value_13,
                14 => $value_14,
                15 => $value_15,
                16 => $value_16,
                17 => $value_17,
                18 => $value_18,
                19 => $value_19,
                20 => $value_20,
                21 => $value_21,
                22 => $value_22,
                23 => $value_23,
                24 => $value_24,
                25 => $value_25,
                26 => $value_26,
                27 => $value_27,
                28 => $value_28,
                29 => $value_29,
                30 => $value_30,
                31 => $value_31,
                32 => $value_32,
                33 => $value_33,
                34 => $value_34,
                35 => $value_35,
        ); 

        $fdata = array('title'=>$title,'valuedata'=>$valuedata);

        return $fdata; 

       //die;
   }

}


?>