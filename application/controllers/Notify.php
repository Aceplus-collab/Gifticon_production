<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends CI_Controller {

	function __construct()
	{   
		parent::__construct();
	}

	public function index()
	{
		define("MID", '11748');			// Merchant ID assigned by IE Pay
		define("API_KEY", 'b94b5b6d0d4bdfbd3d1c66e99c9189c3');		// API KEY assigned by IE Pay

		$params['code'] = $_POST['code'];
		$params['pid'] = $_POST['pid'];
		$params['device_owner'] = $_POST['device_owner'];
		$params['timestamp'] = $_POST['timestamp'];

		$datetime = $_POST['datetime'];			// date time string our server is using GMT+8 Beijing Time
		$sign = $_POST['sign'];
		$signType = $_POST['sign_type'];		// same with the code

		if(isset($_POST['device']) && $_POST['device'] != '')
		{
			$scanner_id = $_POST['device'];	
		}else{
			$scanner_id = "";
		}

		

		$calSign = $this->sign($params, $signType);

		/*if($calSign == $sign)
		{*/
			$tesrt = $_POST['code'];

			$p = (explode("_",$tesrt));

			$busines_id = $p[1];

			$purchase_id = $p[3];

			$purchaseddata = $this->db->get_where('tbl_purchases',array('id'=>$purchase_id,'business_id'=>$busines_id))->row_array();
			if($purchaseddata)
			{

				if($purchaseddata['is_redeem'] == 1)
				{
					echo json_encode(['success' => false, 'error_code' => 100, 'message' => 'Already redeem', 'data' => []]);
					exit;
				}else{

					$prm = array('is_redeem'=>1,'redeem_date'=>date('Y-m-d H:i'));

					if($scanner_id != "")
					{
						$prm['scanner_id'] = $scanner_id;
					}

					$this->db->update('tbl_purchases',$prm,array('id'=>$purchase_id));

			
					//$this->db->insert('tbl_contactus',array('subject'=>$_POST['code']));

					echo json_encode(['success' => true, 'error_code' => 0, 'message' => 'OK', 'data' => []]);
					exit;
				}

					
			}else{
				echo json_encode(['success' => false, 'error_code' => 100, 'message' => 'ERROR', 'data' => []]);
				exit;
			}	
		//}
		/*else
		{
			// SIGN FAILED
			// This request may not send by IE Pay
			echo json_encode(['success' => false, 'error_code' => 100, 'message' => 'ERROR', 'data' => []]);
			exit;
		}*/
	}

	function sign($params,$method = 'MD5')
	{
		ksort($params);

		$paramStr = $this->build_query($params);

		$paramStr .= API_KEY;

		
		if($method == 'MD5')
		{
			return md5($paramStr);
		}

		if($method == 'SHA256')
		{
			return hash('sha256', $paramStr);
		}
	}

	function build_query($params)
	{
		foreach($params as $key => $val)
		{
			$pieces[] = $key . '=' . $val;
		}
		return implode("&", $pieces);
	}

}
?>