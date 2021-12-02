<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Business extends REST_Controller
{
	function __construct()
	{
		parent::__construct();        
        $this->load->model('api/Business_model');
        $this->load->model('api/User_model');
        $this->per_page = 10;
        $this->common->validate_header_token($this);
       
    }
    
	/**
       * @param name
       * @param username
       * @param email
       * @param password
       * @param address
       * @param gender
       * @param phone
       * @param postcode
       * @param dob
       * @param profile_image   
       This function is used to signup
    */
	function signup_post()
	{
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[tbl_businesses.username]',array("required"=>$this->lang->line("text_validation_username"),"is_unique"=>$this->lang->line("text_validation_username1")));
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[tbl_businesses.email]',array("required"=>$this->lang->line("text_validation_email"),"is_unique"=>$this->lang->line("text_validation_email1")));
        
        if($this->form_validation->run())
        {
        	extract($this->input->post());
			$request_data=$this->input->post();
			$request_data['password']=hash('sha256', $request_data['password']);
            $request_data['token']=$this->common->generateToken();
            
            if(isset($request_data['image']) && $request_data['image'] != ""){
                $request_data['image'] = $request_data['image'];
            }else{
                $request_data['image'] = 'default.png';
            }
            
            $user_id = $this->Business_model->add_user($request_data);
			if(isset($user_id) && $user_id != "")
			{
                for($i=1;$i<=3;$i++)
                {
                    $this->db->insert('tbl_business_country',array('business_id'=>$user_id,'gift_country_id'=>$i));
                }

				$user_detail = $this->Business_model->get_user($user_id);

                /*$AllUsers = $this->db->get_where('tbl_user',array('is_active'=>1,'is_delete'=>0))->result_array();

                $NotiMessage = 'New CLient Updated - '.$request_data['name'];

                foreach($AllUsers as $key => $value)
                {
                    $this->db->insert('tbl_notification',array('title'=>$NotiMessage,'user_id'=>$value['id'],'user_type'=>'U','message'=>$NotiMessage,'tag'=>'new','type'=>'new'));

                    $UserDetails = $this->db->get_where('tbl_user',array('id'=>$value['id']))->row_array();

                    $msgpush = array("body"=>$NotiMessage,'title'=>$NotiMessage,"tag"=>'new',"type"=>'new');

                    $body = array(); 
                    $bodyI['aps'] = array('sound'=>'default','mutable-content'=>1, 'alert' => array('title'=>$NotiMessage,'body'=>$NotiMessage),"tag"=>'new');

                    if($UserDetails['device_type']=='A' && $UserDetails['device_id']!='')
                    {
                        $registatoin_ids_D = $UserDetails['device_id'];
                        $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                    }

                    if($UserDetails['device_type']=='I' && $UserDetails['device_id']!='')
                    {
                        $this->common->send_notification_ios_customer($bodyI,$UserDetails['device_id']);
                    }
                }*/

				$message = ['code' => '1','message' =>$this->lang->line("signup_successfully"),"data"=>$user_detail];
                $this->response($message, REST_Controller::HTTP_OK);
			}
        }
        else
        {
        	$fields_validation = $this->validation_errors();
            $message = ['code' => '0','message' => (string)$fields_validation[0]];
            $this->response($message, REST_Controller::HTTP_OK);
        }
	}
	

    function login_post()
	{
		$this->form_validation->set_rules('email','Email','required',array("valid_email"=>$this->lang->line("text_validation_email2"),"required"=>$this->lang->line("text_validation_email")));
		$this->form_validation->set_rules('password','Password','required',array("required"=>"Please Enter Password"));
		if($this->form_validation->run())
		{
			extract($this->input->post());
			$request_data=$this->input->post();

			$this->db->select("*");
			$this->db->from('tbl_businesses');
			$this->db->where(" (email = '".$request_data['email']."' OR username = '".$request_data['email']."' ) AND password = '".hash('sha256', $password)."' AND  is_delete = 0 ");
			$query = $this->db->get();
			$user_data = $query->row_array();
			
            if((isset($device_id) && $device_id!="") && (isset($device_type) && $device_type!=""))
			{
				$data['device_id']=$device_id;
				$data['device_type']=$device_type;
			}
			
			if($user_data)
			{
				if($user_data['is_active']=='0')
				{
					$message=['code'=>'3','message'=>$this->lang->line("account_deactivated")];
					$this->response($message,REST_Controller::HTTP_OK);
				}

				$data['token']=$this->common->generateToken();
				$this->db->update('tbl_businesses',$data,array("id"=>$user_data['id']));
				$user_data=$this->Business_model->get_user($user_data['id']);
				
                $message = ['code' => '1','message' =>$this->lang->line("login_successfully"),"data"=>$user_data];
                $this->response($message, REST_Controller::HTTP_OK);  
			}
			else
            {
                $message = ['code' => '0','message' =>$this->lang->line("invalid_login")];
                $this->response($message, REST_Controller::HTTP_OK);        
            }
		}
		else
        {
            $fields_validation = $this->validation_errors();            
            $message = ['code' => '0','message' => (string)$fields_validation[0]];
            $this->response($message, REST_Controller::HTTP_OK);
        }
	}
	/**
       * @param user_id
       This function is used to Update user profile
       Which ever field will be posted only that data will be updated
    */

    function updateProfile_post()
    {
       // echo "hello"; die;
     	$this->form_validation->set_rules('user_id', 'User id', 'required',array("required"=>"Please provide User id"));
     	if($this->form_validation->run())
     	{
     		$form_data = $this->input->post();
            $user_id = $form_data['user_id'];
            $user_data = $this->Business_model->get_user($user_id);
           
     		if($user_data)
     		{
     			
                if(isset($form_data['image']) && $form_data['image'] != '')
                {
                    $form_data['image'] = $form_data['image'];
                }
                
                unset($form_data['user_id']);
                $msgflg = '0';

                if(isset($form_data['email']) && $form_data['email'] !='')
                {
                	if($form_data['email'] != $user_data['email'])
                	{
                		$checkEmail = $this->db->get_where("tbl_businesses",array("email"=>$form_data['email'],"id !="=>$user_data['id']))->row_array();
                		if($checkEmail)
                		{
                			$message = ['code' => '0','message' =>$this->lang->line("text_validation_email1")];
                			$this->response($message, REST_Controller::HTTP_OK);
                		}
                	}
                }
                $user_detail = $form_data;

                if($this->Business_model->update_user($user_id,$user_detail))
                {
                	$user_data = $this->Business_model->get_user($user_id);
                    $message = ['code' => '1','message' =>$this->lang->line("profile_update"),"data"=>$user_data];
                    $this->response($message, REST_Controller::HTTP_OK);
                }
                else
                {
                    $message = ['code' => '0','message' => $this->lang->line("something_went_wrong")];
                    $this->response($message, REST_Controller::HTTP_OK);
                }
     		}
     		else
            {
                $message = ['code' => '0','message' =>$this->lang->line("user_not_found")];
                $this->response($message, REST_Controller::HTTP_OK);
            }
     	}
     	else
     	{
     		$fields_validation = $this->validation_errors();
            $message = ['code' => '0','message' => (string)$fields_validation[0]];
            $this->response($message, REST_Controller::HTTP_OK);
     	}
    }
    /**
       * @param email
       This function is used for forgot password and if email exists will send reset password link to respective email
    */

    function forgot_password_post()
    {
        extract($this->input->post());
        if(!isset($email) || $email == '')
        {
            $message = ['code' => '0','message' =>"Invalid Parameters"];
            $this->response($message, REST_Controller::HTTP_OK);
        }
        else
        {   
            $user = array();

            $forgot_pass = strtoupper($this->common->generateRandomString(8));
            
            $user =  $this->db->get_where("tbl_businesses",array("email"=>$email))->row_array();
            if($user)
            {
                extract($user);
                $mailConfig = array(
                    "subject"=>"Gifticon Forgot Password",
                    "to"=> $email,
                    "from"=>"info@gifticonofficial.com",
                );

                $data = array("user_detail"=>$user,"forgot_pass"=>$forgot_pass);
                $messageSend = $this->load->view("emailView/forgotpassword",$data,true);
               
                if($this->common->sendMail($mailConfig,$messageSend))
                {
                    $updateData = array("password"=>hash('sha256', $forgot_pass));
                    $is_success = $this->db->update('tbl_businesses',$updateData,array("id"=>$user['id'])); 

                    if($is_success)
                    {
                        $message = ["code"=>'1',"message"=>$this->lang->line("password_sent")];
                        $this->response($message,REST_Controller::HTTP_OK);
                    }
                    else
                    {
                        $message = ["code"=>'0',"message"=>$this->lang->line("account_not_found")];
                        $this->response($message,REST_Controller::HTTP_OK);
                    }
                }
                else{
                    $message = ["code"=>'0',"message"=>$this->lang->line("account_not_found")];
                    $this->response($message,REST_Controller::HTTP_OK);
                }
            }
            else
            {
                $message = ["code"=>'0',"message"=>$this->lang->line("email_not_found")];
                $this->response($message,REST_Controller::HTTP_OK);
            }
        }
    }

    function changePassword_post()
    {
       /* $this->form_validation->set_rules("old_password","Old Password","required");
        $this->form_validation->set_rules("new_password","New Password","required");*/
        $this->form_validation->set_rules("user_id","User Id","required");
       
        if($this->form_validation->run())
        {
            $form_data = $this->input->post();
            $user_detail = array();
            $user_detail = $this->db->get_where("tbl_businesses",array("id"=>$form_data['user_id']))->row_array();
            $upd_data = array();                    
            if($user_detail)
            {
	                if(isset($form_data['old_password']) && $form_data['old_password'] != '')
	                {
	                    if(hash('sha256', $form_data['old_password']) == $user_detail['password'])
	                    {
	                        $upd_data = array("password"=>hash('sha256', $form_data['new_password'])); 
	                    } else
	                    {
	                        $message = ['code' => '0','message' => $this->lang->line("current_wrong")];
	                        $this->response($message, REST_Controller::HTTP_OK);
	                    }    
	                }
                

                    if(isset($form_data['image']) && $form_data['image'] != '')
                    {
                        $upd_data['image'] = $form_data['image'];
                    }

                    if(isset($form_data['website']) && $form_data['website'] != '')
                    {
                        $upd_data['website'] = $form_data['website'];
                    }

                    if(isset($form_data['description']) && $form_data['description'] != '')
                    {
                        $upd_data['description'] = $form_data['description'];
                    }

                    if(isset($form_data['language']) && $form_data['language'] != '')
                    {
                        $upd_data['language'] = $form_data['language'];
                    }

                    if(isset($form_data['bank_account']) && $form_data['bank_account'] != '')
                    {
                        $upd_data['bank_account'] = $form_data['bank_account'];
                    }

                    $condition = array("id"=>$user_detail['id']);
                    $is_success = "";

                    $is_success = $this->db->update('tbl_businesses', $upd_data, $condition);

                    if($is_success)
                    {
                        $message = ['code' => '1','message' => $this->lang->line("profile_changed")];
                    }
                    else
                    {
                        $message = ['code' => '0','message' => $this->lang->line("something_went_wrong")];
                    }                    
                    $this->response($message, REST_Controller::HTTP_OK);
                           
            }
        }
        else
        {
            $fields_validation = $this->validation_errors();            
            $message = ['code' => '0','message' => (string)$fields_validation[0]];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

   	function logout_post()
    {
        extract($this->input->post());
        if( 
            !isset($user_id) || $user_id == "" 
            )
        {   
            $message = ['code' => '0','message' =>'Invalid params'];
            $this->response($message, REST_Controller::HTTP_OK);       
        }
        else
        {
            $condition = array("id"=>$user_id);

            $logout_data = array(
                "token"=>"",
                "device_id"=>"",
                "device_type"=>""
                );
            $is_success = "";
            $is_success = $this->db->update('tbl_businesses', $logout_data, $condition);  

            if($is_success)
            {
                $message = ['code' => '1','message' =>$this->lang->line("logout")];
                $this->response($message, REST_Controller::HTTP_OK);       
            }
            else
            {
                $message = ['code' => '2','message' =>$this->lang->line("something_went_wrong")];
                $this->response($message, REST_Controller::HTTP_OK);              
            }
        }
    }

    function getProfile_post()
    {
        extract($this->input->post());
        if(
            !isset($user_id) || $user_id == "" 
            )
        {
            $message = ['code' => '0','message' => 'Invalid params'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        else
        {   
           $user_detail = $this->Business_model->get_user($user_id);

            if($user_detail)
            {
                $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$user_detail];
                $this->response($message, REST_Controller::HTTP_OK);
            }
            else{
                $message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }

    function contactus_post()
    {
        extract($this->input->post());
        if(
            !isset($user_id) || $user_id == "" || !isset($subject) || $subject == "" )
        {
            $message = ['code' => '0','message' => 'Invalid params'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        else
        {   
            $Allpostdata = $this->input->post();

            $this->db->insert('tbl_contactus',$Allpostdata);

            $mailConfig = array(
                "subject"=>"Gifticon Business App Contact Inquery",
                "to"=> 'phpsyshyperlink@gmail.com', // info@gifticonofficial.com
                "from"=>"info@gifticonofficial.com",
            );

            $user = $this->Business_model->get_user($user_id);
            $user['subject'] = $subject;
            $data = array("user_detail"=>$user);
            $messageSend = $this->load->view("emailView/contactus",$data,true);
           
            $this->common->sendMail($mailConfig,$messageSend);
            
            $message = ['code' => '1','message' =>$this->lang->line("contact_us")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function getIdsFromBarcode_post()
    {
        extract($this->input->post());
        if(!isset($barcode_string) || $barcode_string == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        /*$message = ['code' => '4','message' => "Your scan string is :".$barcode_string];
            $this->response($message, REST_Controller::HTTP_OK);*/

        $purchaseddata = $this->db->get_where('tbl_purchases',array('barcode_string'=>$barcode_string))->row_array();
        if($purchaseddata)
        {
            $data['ids'] = $purchaseddata['barcode_string_ids'];

            $message = ['code' => '1','message' => 'data found','data'=>$data];
            $this->response($message, REST_Controller::HTTP_OK);

            /*$message = ['code' => '4','message' => "Your scan string is :".$barcode_string];
            $this->response($message, REST_Controller::HTTP_OK);*/

        }else{

            $message = ['code' => '4','message' => $this->lang->line("invalid_qr")];
            $this->response($message, REST_Controller::HTTP_OK); 
        }

    }

   	function qrScan_post()
    {
    	extract($this->input->post());
		if(!isset($gifticon_id) || $gifticon_id == "" || $user_id == "" || $customer_user_id == "" || $purchase_id == "")
        // if(!isset($barcode_string) || $barcode_string == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $current_date_time = date('Y-m-d H:i');
        
        // ,'business_id'=>$user_id
        $purchaseddata = $this->db->get_where('tbl_purchases',array('id'=>$purchase_id,'user_id'=>$customer_user_id))->row_array();

        if($purchaseddata)
        {
                if($user_id != $purchaseddata['business_id'])
                {
                    $message = ['code' => '4','message' => 'Invalid code for this business!']; 
                    $this->response($message, REST_Controller::HTTP_OK);
                }
                
                $purchaseddata['giftdetails'] = $this->User_model->getGift($purchaseddata['gifticon_id']);

                if($customer_user_id == $purchaseddata['user_id'])
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
                
            if($purchaseddata['is_redeem'] == 0)
            {
                $this->db->update('tbl_purchases',array('is_redeem'=>1,'redeem_date'=>$current_date_time),array('id'=>$purchase_id));

                $receiver_detail = $this->User_model->get_user($purchaseddata['user_id']);

                $mssg = "QR code has been redeem";

                $msgpush = array("message"=>$mssg,'badge'=>0,'purchase_id'=>$purchase_id,"tag"=>'redeem');

                $body = array(); 
                $bodyI['aps'] = array('sound'=>'','content-available'=>1,'mutable-content'=>1,'badge'=>0,'alert' => array(),'purchase_id'=>$purchase_id,"tag"=>'redeem'); // 'title' => $mssg

                if($receiver_detail['device_type']=='A' && $receiver_detail['device_id'] !='' )
                {
                    $registatoin_ids_D = $receiver_detail['device_id'];
                    $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                }

                if($receiver_detail['device_type']=='I' && $receiver_detail['device_id']!='')
                {
                    $this->common->send_notification_ios_customer_new($bodyI,$receiver_detail['device_id']);
                }

                if($purchaseddata['giftto_user_id'] != 0)
                {
                    $gifttouserdetail = $this->User_model->get_user($purchaseddata['giftto_user_id']);
                 	
	                if($gifttouserdetail['device_type']=='A' && $gifttouserdetail['device_id'] !='' )
	                {
	                    $registatoin_ids_D = $gifttouserdetail['device_id'];
	                    $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
	                }

	                if($gifttouserdetail['device_type']=='I' && $gifttouserdetail['device_id']!='')
	                {
	                    $this->common->send_notification_ios_customer_new($bodyI,$gifttouserdetail['device_id']);
	                }   
                }

                $message = ['code' => '1','message' => $this->lang->line("qr_redeem"),'data'=>$purchaseddata];
                $this->response($message, REST_Controller::HTTP_OK);

            }else{
                $message = ['code' => '2','message' => $this->lang->line("rq_already_redeem"),'data'=>$purchaseddata];
                $this->response($message, REST_Controller::HTTP_OK); 
            }
        }else{
            $message = ['code' => '4','message' => $this->lang->line("invalid_qr")]; 
            $this->response($message, REST_Controller::HTTP_OK); 
        }
	}

    /*function qrScan_post()
    {
        extract($this->input->post());
        if(!isset($gifticon_id) || $gifticon_id == "" || $user_id == "" || $customer_user_id == "" || $purchase_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $current_date_time = date('Y-m-d H:i');
        
        $purchaseddata = $this->db->get_where('tbl_purchases',array('id'=>$purchase_id,'business_id'=>$user_id,'user_id'=>$customer_user_id))->row_array();

        if($purchaseddata)
        {
                $purchaseddata['giftdetails'] = $this->User_model->getGift($purchaseddata['gifticon_id']);

                if($customer_user_id == $purchaseddata['user_id'])
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
                
            if($purchaseddata['is_redeem'] == 0)
            {
                $this->db->update('tbl_purchases',array('is_redeem'=>1,'redeem_date'=>$current_date_time),array('id'=>$purchase_id));

                $receiver_detail = $this->User_model->get_user($purchaseddata['user_id']);

                $mssg = "QR code has been redeem";

                $msgpush = array("message"=>$mssg,'badge'=>0,'purchase_id'=>$purchase_id,"tag"=>'redeem');

                $body = array(); 
                $bodyI['aps'] = array('sound'=>'','content-available'=>1,'mutable-content'=>1,'badge'=>0,'alert' => array(),'purchase_id'=>$purchase_id,"tag"=>'redeem'); // 'title' => $mssg

                if($receiver_detail['device_type']=='A' && $receiver_detail['device_id'] !='' )
                {
                    $registatoin_ids_D = $receiver_detail['device_id'];
                    $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                }

                if($receiver_detail['device_type']=='I' && $receiver_detail['device_id']!='')
                {
                    $this->common->send_notification_ios_customer($bodyI,$receiver_detail['device_id']);
                }

                if($purchaseddata['giftto_user_id'] != 0)
                {
                    $gifttouserdetail = $this->User_model->get_user($purchaseddata['giftto_user_id']);
                    
                    if($gifttouserdetail['device_type']=='A' && $gifttouserdetail['device_id'] !='' )
                    {
                        $registatoin_ids_D = $gifttouserdetail['device_id'];
                        $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                    }

                    if($gifttouserdetail['device_type']=='I' && $gifttouserdetail['device_id']!='')
                    {
                        $this->common->send_notification_ios_customer($bodyI,$gifttouserdetail['device_id']);
                    }   
                }

                $message = ['code' => '1','message' => $this->lang->line("qr_redeem"),'data'=>$purchaseddata];
                $this->response($message, REST_Controller::HTTP_OK);

            }else{
                $message = ['code' => '2','message' => $this->lang->line("rq_already_redeem"),'data'=>$purchaseddata];
                $this->response($message, REST_Controller::HTTP_OK); 
            }
        }else{
            $message = ['code' => '4','message' => $this->lang->line("invalid_qr")]; 
            $this->response($message, REST_Controller::HTTP_OK); 
        }
    }*/

	function productDetail_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $product_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $this->db->select("tbl_gifticons.*");
        $this->db->from('tbl_gifticons');
        $this->db->where('tbl_gifticons.id',$product_id);
        $this->db->where("tbl_gifticons.is_active = 1 AND tbl_gifticons.is_delete = 0");
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $products = $query->row_array();
            $products['image'] = GIFT_IMAGE.$products['image'];
            $products['business'] = $this->Business_model->get_business($products['business_id']);
            
	        $this->db->select("tbl_gifticons_tags.*,tbl_tags.image");
	        $this->db->from('tbl_gifticons_tags');
	        $this->db->from('tbl_tags');
	        $this->db->where(" tbl_gifticons_tags.tag_id = tbl_tags.id AND tbl_gifticons_tags.gifticon_id = '".$products['id']."' ");
	        $query = $this->db->get();
	        $tag = $query->row_array();

	        $products['tag_image'] = BRAND_IMAGE.$tag['image'];

            $message = ['code' => '1','message' => $this->lang->line("data_found"),'data'=>$products];
        	$this->response($message, REST_Controller::HTTP_OK);
        }else{
        	$message = ['code' => '2','message' => $this->lang->line("no_data_found")];
        	$this->response($message, REST_Controller::HTTP_OK);
        }
	}

    function getHomecount_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $data = array();
        $totalSold = 0;
        $totalRedeem = 0;

        $gifticons = $this->db->get_where('tbl_gifticons',array('business_id'=>$user_id))->result_array();
        if($gifticons)
        {
			foreach ($gifticons as $key => $value) 
			{
				$giftidsArray[] = $value['id'];
			}	

			$giftids = implode(',', $giftidsArray);

			$this->db->select("tbl_purchases.*");
	        $this->db->from('tbl_purchases');
	        $this->db->where(" business_id = ".$user_id." ");
	        $query = $this->db->get();
	        $totalSold = $query->num_rows();

	        $this->db->select("tbl_purchases.*");
	        $this->db->from('tbl_purchases');
	        $this->db->where(" business_id = ".$user_id." AND is_redeem = 1 ");
	        $query = $this->db->get();
	        $totalRedeem = $query->num_rows();
			 
        }

        /*$totalSold = 0;
        $tatalRedeem = 0;*/
        $data['totalSold'] = $totalSold;
        $data['totalRedeem'] = $totalRedeem;

        $noticount = $this->db->get_where('tbl_notification',array('user_id'=>$user_id,'user_type'=>'B','is_read'=>0))->num_rows();

		$data['unread_noti_count'] = $noticount;

        $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$data];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    function redeemHistory_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $data = array();

        $this->per_page = 20;

        if(!isset($page) || $page == "" || $page == "0")
        {
            $page = 1;
        }

        $this->db->select("*");
        $this->db->from('tbl_purchases');
        $this->db->where(" business_id = '".$user_id."' AND is_redeem = '1' order by last_update DESC LIMIT ".(($page-1) * $this->per_page).",".$this->per_page." ");
        $queryGrp = $this->db->get();
        $purchase = $queryGrp->result_array();

        if($purchase)
        {
            foreach ($purchase as $key => $value) 
            {
                $giftdetails = $this->User_model->getGift($value['gifticon_id']);

                $purchase[$key]['giftdetails'] = $giftdetails;
            }   

            $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$purchase];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{

            $message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);    
        }
    }

    function notificationList_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $noti = $this->db->order_by('id','desc')->get_where('tbl_notification',array('user_id'=>$user_id,'user_type'=>'B'))->result_array();
        if($noti)
        {
            $this->db->update('tbl_notification',array('is_read'=>1),array('user_id'=>$user_id,'user_type'=>'B'));
            /*foreach ($noti as $key => $value) 
            {
                $purchasedata = array();
                if($value['type'] == 'receipt')
                {
                    $pids = explode(',', $value['purchase_id']);
                    foreach ($pids as $inkey => $pvalue) {
                        $purchasedata[] = $this->User_model->purchasedetail($user_id,$pvalue);
                    }
                }else{
                    $purchasedata = array();
                }    

                $noti[$key]['purchasedata'] = $purchasedata;
            }*/

            $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$noti];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

   
}
?>