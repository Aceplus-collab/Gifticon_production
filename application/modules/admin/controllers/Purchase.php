<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Purchase extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('purchase_model');
        // $this->load->library('session');
		// $this->load->library('form_validation');

        $data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
        }

        if(!empty($_REQUEST['limit']))
        {
            $page = ($_REQUEST['offset'] != 0) ? ($_REQUEST['offset'] / $_REQUEST['limit'])+1 : 1;
            $this->session->set_userdata('page', $page);
        }
        if(!empty($this->uri->segment(3)) && !in_array($this->uri->segment(3), array("listing")))
        {
            $this->session->set_userdata($this->uri->segment(2).'_curr_page', $this->session->userdata('page'));
        }
	}

    function listing($isedit=0)
    {   
        if(isset($isedit) && $isedit == 0)
        {
            $this->session->unset_userdata('user_curr_page');    
        }

        $data['admin']=$this->session->userdata();
        $data['page']="purchase";
        $this->load->view('purchase/list',$data);
    }

    function purchase_ajax_list()
    {
        $category_list = $this->purchase_model->getPurchase();
        $data = array();
        if(!empty($category_list)) 
        {
            foreach ($category_list as $key => $category) {
                $row = array();
                $row['id'] = $key+1;

                $row['username'] = $category['username'];
                $row['country'] = $category['country'];
                $row['gift_name'] = $category['gift_name'];
                $row['business_name'] = $category['business_name'];
                $row['scanner_id'] = $category['scanner_id'];
                $row['gift_image'] = '<img class="img-responsive img-circle img-thumbnail thumb-md" src='.GIFT_IMAGE.$category['gift_image'].' height="60" width="60"  alt="No image">';
                
                 if($category['gifticon_type'] == 0)
                {
                    $row['gifticon_type'] = 'gifticon';
                    $row['giftcard_format'] = 'QR Code <br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="QR">';
                }else{
                    $row['gifticon_type'] = 'giftcard';
                }
                
                if($category['giftcard_format'] == 0)
                {
                    $row['giftcard_format'] = 'Plain Code: '.$category['plain_code'].'<br>'. 'Pin : '.$category['pin'];
                }/*elseif($category['giftcard_format'] == 1)
                {
                    $row['giftcard_format'] = 'QR Code <br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="QR">';
                }*/elseif($category['giftcard_format'] == 2)
                {
                    $row['giftcard_format'] = 'Barcode string : '.$category['plain_code'].'<br>'. 'Pin : '.$category['pin'];
                }

                $row['normal_price'] = $category['normal_price'];

                $row['price'] = $category['price'];

                $row['coupon_discount_amount'] = $category['coupon_discount_amount'];

                $row['is_redeem'] = ($category['is_redeem'] == '1') ? "Yes" : "No";

                $row['purchase_date'] = $category['purchase_date'];

                $row['redeem_date'] = $category['redeem_date'];
                $row['purchase_id'] = $category['purchase_id'];

                $row['giftto_user_name'] = $category['giftto_user_name'];
                $row['giftfrom_user_name'] = $category['giftfrom_user_name'];

                $errorStatus = include APPPATH.'/config/wincube_error_code.php';

                $row['response_reason'] = $errorStatus[$category['response_reason']];

                $row['currency'] = $category['currency'] ? strtoupper($category['currency']) : '-';
                if($category['wincube_id'] != null)
                {
                    if($category['voucher_status'] == "cancelled")
                    {
                        if($category['voucher_cancel_time'] != null)
                        {
                            $voucher_cancel_date = new DateTime($category['voucher_cancel_time']);
                            $voucher_cancel_date = $voucher_cancel_date->format('Y-m-d H:i:s');
                        }else{
                            $voucher_cancel_date = '-';
                        } 
                        $row['voucher_status'] = '<span class="text-center">'. ucfirst($category['voucher_status']) .'</br>'. $voucher_cancel_date .'</span>';
                        $row['wincube_id'] = '-';
                    }else{

                        if($category['voucher_status'] != "cancelled" && $category['voucher_status'] != null)
                        {
                            $row['voucher_status'] = '<span class="text-center">'.$errorStatus[$category['voucher_status']].'</span>';
                        }else{
                            $row['voucher_status'] = '<span class="text-center">---</span>';
                        }

                        $row['wincube_id'] = '<button class="cancel-voucher-excel-btn" data-purchase-id='. $category['purchase_id'] .' data-wincube-id='. $category['wincube_id'] .'>Cancel Voucher</button>';
                    }

                    // if($category['voucher_status'] != null)
                    // {
                    //     if($category['voucher_cancel_time'] != null)
                    //     {
                    //         $voucher_cancel_date = new DateTime($category['voucher_cancel_time']);
                    //         $voucher_cancel_date = $voucher_cancel_date->format('Y-m-d H:i:s');
                    //     }else{
                    //         $voucher_cancel_date = '-';
                    //     }
                        
                    //     $row['voucher_status'] = '<span class="text-center">'. ucfirst($category['voucher_status']) .'</br>'. $voucher_cancel_date .'</span>';
                    //     // $row['wincube_id'] = '<button class="recancel-voucher-excel-btn" data-purchase-id='. $category['purchase_id'] .' data-wincube-id='. $category['wincube_id'] .'>Recancel Voucher</button>';
                    //     $row['wincube_id'] = '-';
                    // }else{
                    //     $row['voucher_status'] = '<span class="text-center"> -- </span>';

                    //     $row['wincube_id'] = '<button class="cancel-voucher-excel-btn" data-purchase-id='. $category['purchase_id'] .' data-wincube-id='. $category['wincube_id'] .'>Cancel Voucher</button>';
                    // }

                    if($category['response_reason'] == '0')
                    {
                        $row['mm_resend'] = '-';
                    }else{
                        $row['mm_resend'] = '<button class="mm-resend-btn" data-purchase-id='. $category['purchase_id'] .' data-wincube-id='. $category['wincube_id'] .'>Resend</button>';
                    }

                }else{
                    $row['voucher_status'] = '-';
                    $row['wincube_id'] = '-';
                    $row['mm_resend'] = '-';
                }

                $data[] = $row;
            }
        }
        if(isset($_REQUEST['export_excel']))
        {
            return $this->ajax_excel_export();
        }
        $output = array(
            "total" => $this->purchase_model->count_filtered_purchase(),
            "rows" => $data
            );
        echo json_encode($output);
    }

    function ajax_excel_export()
    {
        $fileName = date("Y-m-d")."gifticon-voucher.xls";
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array("ID","Country", "Purchase Date", "Username", "Gift Name", "Business Name", "Scanner ID", "Gifticon Type", "Currency", "Normal Price", "Price", "Coupon Discount Amount", "Is Redeem", "Redeem Date", "Gift To User Name", "Gift From Username");

        $column = 0;

        foreach($table_columns as $field)
        {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $purchases = $this->purchase_model->getPurchase();
        $excel_row = 2;
        if($purchases)
        {
            foreach($purchases as $row)
            {
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['id']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['country']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['purchase_date']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['username']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['gift_name']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['business_name']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['scanner_id']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['gifticon_type']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, strtoupper($row['currency']));
                $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row['normal_price']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row['price']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row['coupon_discount_amount']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row['is_redeem']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row['redeem_date']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row['giftto_user_name']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row['giftfrom_user_name']);
                $excel_row++;
            }
    
            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=$fileName");
            $object_writer->save('php://output');
            exit;
        }
 
    }

    function country_ajax_list()
    {
        $data = $this->db->get_where('tbl_gift_country')->result_array();
        echo json_encode($data);
    }

    function ajax_voucher_cancel()
    {
    	$wincube_id=$_POST['wincube_id'];
        $purchase_id=$_POST['purchase_id'];
	    $client = new GuzzleHttp\Client();
        $purchase = $this->db->get_where('tbl_purchases',array('id'=>$purchase_id))->row_array();

        if($purchase['is_redeem'] == 1)
        {
            echo json_encode(['error' => "This item is can't cancel, becuase already redeem", 'wincube_response' => null]);
            die;
        }else{

            $status_res = $client->request('POST', WINCUBE_API_BASE . 'coupon_status.do', [
                'query' => [
                    'mdcode' => 'gifticon_nz',
                    'response_type' => 'JSON',
                    'tr_id' => $purchase_id
                ]
            ]);
            $status_body = mb_convert_encoding($status_res->getBody(), 'UTF-8', 'EUC-KR');
            $status = json_decode($status_body, true);
            if (isset($status['StatusCode']) && $status['StatusCode'] == 4006) {
                $this->db->update('tbl_purchases', [
                    'is_redeem' => 1,
                    'redeem_date' => date_format(date_create($status['SwapDt']), 'Y-m-d H:i:s')
                ], ['id' => $purchase_id]);
                echo json_encode(['error' => "This item is can't cancel, becuase already redeem", 'wincube_response' => null]);
                die;
            }else{

                if($purchase['wincube_ctr_id'] != null)
                {

                    $res = $client->request('POST', WINCUBE_API_BASE . 'coupon_cancel.do', [
                        'query' => [
                            'mdcode' => 'gifticon_nz',
                            'tr_id' => (int)$purchase_id,
                            'response_type' => 'JSON'
                        ]
                    ]);
                    $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
                    $goods = json_decode($body, true);
                    if (empty($goods)) {
                        echo json_encode(['error' => 'Something was wrong querying WinCube', 'wincube_response' => $body]);
                        die;
                    }else{
                        if($goods['StatusCode'] == "0")
                        {
                            $this->db->update('tbl_purchases',array('voucher_status'=>'cancelled'),array('id'=>$purchase_id));
                            $this->db->update('tbl_purchases',array('voucher_cancel_time'=>$goods['cancelDateTime']),array('id'=>$purchase_id));
                            $output = array("success" => 'Purchase cancelled successfully!');
                            echo json_encode($output);
                        }else{
                            $errorStatus = include APPPATH.'/config/wincube_error_code.php';
                            $resposne_reason = $goods['StatusCode'];
    
                            $this->db->update('tbl_purchases', ['response_reason' => $resposne_reason], ['id' => $purchase_id]);
    
                            $output = array("error" => $errorStatus[$resposne_reason]);
                            // $output = array("error" => 'Something was wrong querying WinCube!');
                            echo json_encode($output);
                        }
                    }
                }else{
                    $dateTime = date('YmdHis');
                    $this->db->update('tbl_purchases',array('voucher_status'=>'cancelled'),array('id'=>$purchase_id));
                    $this->db->update('tbl_purchases',array('voucher_cancel_time'=>$dateTime),array('id'=>$purchase_id));
                    $output = array("success" => 'Purchase cancelled successfully!');
                    echo json_encode($output);
                }
            }
        }
    }

    function ajax_voucher_recancel()
    {
        $wincube_id=$_POST['wincube_id'];
        $purchase_id=$_POST['purchase_id'];
        $this->db->update('tbl_purchases',array('voucher_status'=>null),array('id'=>$purchase_id));
        $output = array("success" => 'Purchase recancelled successfully!');
        echo json_encode($output);
    }

    function mmresend()
    {
    	$wincube_id=$_POST['wincube_id'];
        $purchase_id=$_POST['purchase_id'];
        $client = new GuzzleHttp\Client();

        try {

            $res = $client->request('POST', WINCUBE_API_BASE . '/resend.do', [
                'query' => [
                    'mdcode' => 'gifticon_nz',
                    'response_type' => 'JSON',
                    'tr_id' => $purchase_id
                ]
            ]);
            $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
            $goods = json_decode($body, true);

            if($goods['result_code'] == 0)
            {
                $output = array("success" => 'Message resend successfully!');
                $this->db->update('tbl_purchases', ['response_reason' => '01'], ['id' => $purchase_id]);
                echo json_encode($output);
            }else{
            
                $errorStatus = include APPPATH.'/config/wincube_error_code.php';
                $resposne_reason = $goods['result_code'];
                
                $output = array("success" => $errorStatus[$resposne_reason]);
                $this->db->update('tbl_purchases', ['response_reason' => $resposne_reason], ['id' => $purchase_id]);
                echo json_encode($output);
            }
        } catch (Exception $e) {
            $output = array("error" => 'Something Wrong!');
            echo json_encode($output);
        }
    }

}
?>
