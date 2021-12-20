<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class User extends REST_Controller
{
	function __construct()
	{
		parent::__construct();        
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
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[tbl_user.username]',array("required"=>$this->lang->line("text_validation_username"),"is_unique"=>$this->lang->line("text_validation_username1")));
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[tbl_user.email]',array("required"=>$this->lang->line("text_validation_email"),"is_unique"=>$this->lang->line("text_validation_email1")));
        $this->form_validation->set_rules('signup_type', 'Signup Type', 'required');
        
        if($this->form_validation->run())
        {
        	extract($this->input->post());
			$request_data=$this->input->post();
			$request_data['password']=hash('sha256', $request_data['password']);
            $request_data['token']=$this->common->generateToken();
            $request_data['is_login'] = 1;

			if(isset($request_data['profile_image']) && $request_data['profile_image'] != ""){
                $request_data['profile_image'] = $request_data['profile_image'];
            }else{
                $request_data['profile_image'] = 'default.png';
            }
            
            $user_id = $this->User_model->add_user($request_data);
			if(isset($user_id) && $user_id != "")
			{
				$user_detail = $this->User_model->get_user($user_id);
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
	/**
       * @param user_id
       * @param otp
       This function is used to verify otp
    */

    function checkSocial_post()
    {
        $this->form_validation->set_rules('social_id', 'Social Id', 'required');
        if($this->form_validation->run())
        {   
            $user_detail = array();

            extract($this->input->post());

            $user_detail = $this->db->get_where('tbl_user',array('social_id'=>$social_id))->row_array();
            
            if($user_detail)
            {  
                if($user_detail['is_active'] == "0")
                {
                   $message=['code'=>'3','message'=>$this->lang->line("account_deactivated")];
                    $this->response($message,REST_Controller::HTTP_OK);
                }
                
                $data = array(                        
                    "token"=>$this->common->generateToken(),
                    "is_login" =>"1"
                    );
                if((isset($device_id) && $device_id!="") && (isset($device_type) && $device_type !=""))
                {
                    $data['device_id'] = $device_id;
                    $data['device_type'] = $device_type;
                }

                if((isset($device_os) && $device_os!=""))
                {
                    $data['device_os'] = $device_os;
                }

                if((isset($device_model) && $device_model!=""))
                {
                    $data['device_model'] = $device_model;
                }

                if((isset($app_version) && $app_version!=""))
                {
                    $data['app_version'] = $app_version;
                }

                

                if((isset($lat) && $lat!="") && (isset($lng) && $lng !=""))
                {
                    $data['lat'] = $lat;
                    $data['lng'] = $lng;
                }
                $condition = array("id"=>$user_detail['id']);
                $this->db->update('tbl_user', $data, $condition);

                $user_data = $this->User_model->get_user($user_detail['id']);

                $message = ['code' => '1','message' =>$this->lang->line("login_successfully"),"data"=>$user_data];
                $this->response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = ['code' => '2','message' => $this->lang->line("user_not_found")];
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

    function deviceInformation_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || !isset($device_id) || $device_id == "" || !isset($device_type) || $device_type == "" || !isset($device_os) || $device_os == "" || !isset($device_model) || $device_model == "" || !isset($app_version) || $app_version == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        $user_data=$this->db->get_where("tbl_user",array('id'=>$user_id))->row_array();
        
        if((isset($device_id) && $device_id!="") && (isset($device_type) && $device_type!=""))
        {
            $data['device_id']=$device_id;
            $data['device_type']=$device_type;
        }

        if((isset($device_os) && $device_os!=""))
        {
            $data['device_os'] = $device_os;
        }

        if((isset($device_model) && $device_model!=""))
        {
            $data['device_model'] = $device_model;
        }

        if((isset($app_version) && $app_version!=""))
        {
            $data['app_version'] = $app_version;
        }
        if($user_data)
        {
            $this->db->update('tbl_user',$data,array("id"=>$user_data['id']));
            $user_data=$this->User_model->get_user($user_data['id']);
            
            $message = ['code' => '1','message' =>"Token refresh successfully!","data"=>$user_data];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        else
        {
            $message = ['code' => '0','message' => "Invaild user!"];
            $this->response($message, REST_Controller::HTTP_OK);        
        }
    }

    function login_post()
	{
		$this->form_validation->set_rules('email','Email','required|valid_email',array("valid_email"=>$this->lang->line("text_validation_email2"),"required"=>$this->lang->line("text_validation_email")));
		$this->form_validation->set_rules('password','Password','required',array("required"=>"Please Enter Password"));
		if($this->form_validation->run())
		{
			extract($this->input->post());
			$request_data=$this->input->post();
			$condition=array("email"=>$email,"password"=>hash('sha256', $password),"is_delete"=>'0');
			$user_data=$this->db->get_where("tbl_user",$condition)->row_array();
			
            if((isset($device_id) && $device_id!="") && (isset($device_type) && $device_type!=""))
			{
				$data['device_id']=$device_id;
				$data['device_type']=$device_type;
			}
			if((isset($lat) && $lat!="") && (isset($lng) && $lng!=""))
			{
				$data['lat']=$lat;
				$data['lng']=$lng;
			}

            if((isset($device_os) && $device_os!=""))
            {
                $data['device_os'] = $device_os;
            }

            if((isset($device_model) && $device_model!=""))
            {
                $data['device_model'] = $device_model;
            }

            if((isset($app_version) && $app_version!=""))
            {
                $data['app_version'] = $app_version;
            }
			if($user_data)
			{
				if($user_data['is_active']=='0')
				{
					$message=['code'=>'3','message'=>$this->lang->line("account_deactivated")];
					$this->response($message,REST_Controller::HTTP_OK);
				}

				$data['is_login'] = 1;
				$data['token']=$this->common->generateToken();
				$this->db->update('tbl_user',$data,array("id"=>$user_data['id']));
				$user_data=$this->User_model->get_user($user_data['id']);
				
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
            $user_data = $this->User_model->get_user($user_id);
           
     		if($user_data)
     		{
     			
                if(isset($form_data['profile_image']) && $form_data['profile_image'] != '')
                {
                    $form_data['profile_image'] = $form_data['profile_image'];
                }
                
                unset($form_data['user_id']);
                $msgflg = '0';

                if(isset($form_data['email']) && $form_data['email'] !='')
                {
                	if($form_data['email'] != $user_data['email'])
                	{
                		$checkEmail = $this->db->get_where("tbl_user",array("email"=>$form_data['email'],"id !="=>$user_data['id']))->row_array();
                		if($checkEmail)
                		{
                			$message = ['code' => '0','message' =>$this->lang->line("text_validation_email1")];
                			$this->response($message, REST_Controller::HTTP_OK);
                		}
                	}
                }
                $user_detail = $form_data;

                if($this->User_model->update_user($user_id,$user_detail))
                {
                	$user_data = $this->User_model->get_user($user_id);
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
            
            $user =  $this->db->get_where("tbl_user",array("email"=>$email))->row_array();
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
                    $is_success = $this->db->update('tbl_user',$updateData,array("id"=>$user['id'])); 

                    if($is_success)
                    {
                        $message = ["code"=>'1',"message"=>$updateData];
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
            $user_detail = $this->db->get_where("tbl_user",array("id"=>$form_data['user_id']))->row_array();                    
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
                

                    if(isset($form_data['profile_image']) && $form_data['profile_image'] != '')
                    {
                        $upd_data['profile_image'] = $form_data['profile_image'];
                    }

                    if(isset($form_data['language']) && $form_data['language'] != '')
                    {
                        $upd_data['language'] = $form_data['language'];
                    }

                    $condition = array("id"=>$user_detail['id']);
                    $is_success = "";

                    $is_success = $this->db->update('tbl_user', $upd_data, $condition);

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

    function getCountry_post()
    {
        extract($this->input->post());
        
        $user_detail = $this->db->get_where("tbl_gift_country",array("is_active"=>1))->result_array();
        if($user_detail)
        {
            foreach ($user_detail as $key => $value) {
                $user_detail[$key]['image'] = BRAND_IMAGE.$value['image'];
                $user_detail[$key]['flag'] = BRAND_IMAGE.$value['flag'];
			}
            $message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$user_detail];
            $this->response($message, REST_Controller::HTTP_OK);
        }
        else
        {
            $message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
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
                "lat"=>"",
                "lng"=>"",
                "device_type"=>"",
                "is_login"=>"0"
                );
            $is_success = "";
            $is_success = $this->db->update('tbl_user', $logout_data, $condition);  

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
           $user_detail = $this->User_model->get_user($user_id);

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
            !isset($user_id) || $user_id == "" || !isset($name) || $name == "" || !isset($email) || $email == ""
            )
        {
            $message = ['code' => '0','message' => 'Invalid params'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }
        else
        {   
            $Allpostdata = $this->input->post();

            $this->db->insert('tbl_contactus',$Allpostdata);
            
            $message = ['code' => '1','message' =>$this->lang->line("contact_us")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function getTags_post()
    {
        extract($this->input->post());
        
        $user_detail = $this->db->order_by('order_sequence','ASC')->get_where("tbl_tags")->result_array();
        if($user_detail)
        {
            foreach ($user_detail as $key => $value) {
                $user_detail[$key]['image'] = BRAND_IMAGE.$value['image'];
            }
            $message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$user_detail];
            $this->response($message, REST_Controller::HTTP_OK);
        }
        else
        {
            $message = ['code' => '2','message' => $this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function getBrandList_post()
    {
    	extract($this->input->post());
		if(!isset($user_id) || $user_id == "" || !isset($country_id) || $country_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Params'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $allPost = $this->input->post();

        if(!isset($brand_page) || $brand_page <= 0)
        {
            $brand_page = 1;
        }
        if(!isset($product_page) || $product_page <= 0)
        {
            $product_page = 1;
        }

        $brands = array();
        $products = array();

        $condition_brand = "";
        $condition_proudct = "";
        $condition = "";
        if(isset($search) && $search != '')
        {
            $condition_brand = "AND (tbl_businesses.name like '%".$search."%' or tbl_businesses.username like '%".$search."%')";

            $condition_proudct = "AND (tbl_gifticons.name like '%".$search."%')";    
        }

        $this->db->select("tbl_businesses.*,tbl_business_country.gift_country_id");
		$this->db->from('tbl_businesses');
		$this->db->join('tbl_business_country',"tbl_business_country.business_id= tbl_businesses.id ",'left');
		if(isset($tag_id) && $tag_id != '')
		{
			$condition = "AND tbl_business_tags.tag_id = '".$tag_id."' ";
			$this->db->join('tbl_business_tags',"tbl_business_tags.business_id= tbl_businesses.id ",'left');
		}
		$this->db->where("tbl_businesses.is_active = 1 AND tbl_businesses.is_delete = 0 AND tbl_business_country.gift_country_id = '".$country_id."' ".$condition_brand." ".$condition." group by tbl_businesses.id order by tbl_businesses.sequence ASC "); // LIMIT ".(($brand_page-1) * $this->per_page).",".$this->per_page."
        $this->db->limit($this->per_page, (($brand_page-1) * $this->per_page));
        $query = $this->db->get();
		if($query->num_rows() >= 1)
		{
			$brands = $query->result_array();
			foreach ($brands as $mkey => $mvalue) 
			{
				if($mvalue['image'] != '')
				{
					$brands[$mkey]['image'] = BRAND_IMAGE.$mvalue['image'];
				}
			}
		}

        $this->db->select("tbl_gifticons.*,tbl_businesses.id as business_id");
        $this->db->from('tbl_gifticons');
        $this->db->join('tbl_businesses',"tbl_gifticons.business_id= tbl_businesses.id ",'left');
        $this->db->join('tbl_business_country',"tbl_business_country.business_id= tbl_businesses.id ",'left');
        if(isset($tag_id) && $tag_id != '')
        {
            $this->db->join('tbl_gifticons_tags',"tbl_gifticons_tags.gifticon_id= tbl_gifticons.id ",'left');
            $this->db->where("tbl_gifticons_tags.tag_id = '".$tag_id."' ");
        }
        $this->db->where(" tbl_gifticons.is_active = 1 AND tbl_businesses.is_active = 1 AND tbl_gifticons.is_delete = 0 AND tbl_gifticons.sale_end_date >= '".date('Y-m-d')."' AND tbl_business_country.gift_country_id = '".$country_id."' ".$condition_proudct." ");
        $this->db->group_by("tbl_gifticons.id");
        $this->db->order_by("tbl_gifticons.sequence ASC");
        $this->db->limit($this->per_page, (($product_page-1) * $this->per_page));
        $query = $this->db->get();
        //exchange rate
        $exchange_rate = $this->db->get_where('tbl_exchange_rate',array('ex_country'=>'KRW'))->row_array();
        //exchange rate
        if($query->num_rows() >= 1)
        {
            $products = $query->result_array();
            foreach ($products as $pkey => $pvalue) 
            {
                if (!empty($pvalue['wincube_image'])) {
                    $products[$pkey]['image'] = $pvalue['wincube_image'];
                }
                else if ($pvalue['image'] != '')
                {
                    $products[$pkey]['image'] = GIFT_IMAGE.$pvalue['image'];
                }
                $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$pvalue['id']))->result_array();
            
                $products[$pkey]['gift_size'] = $gift_size;
                $products[$pkey]['qry_remaining'] = $this->User_model->qtyRemaing($pvalue['id']);
                $products[$pkey]['business'] = $this->User_model->get_business($pvalue['business_id']);
                if($pvalue['wincube_id'] != null && isset($isWon) && $isWon == "false")
                {
                    $products[$pkey]['normal_price'] = round((double)$pvalue['normal_price'] / (double)$exchange_rate['rate'], 2);
                    $products[$pkey]['coupon_price'] = round((double)$pvalue['coupon_price'] / (double)$exchange_rate['rate'], 2);
                }
            }
        } 

		$responsedata = array("brands"=>$brands,"products"=>$products);

		$message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$responsedata];
        $this->response($message, REST_Controller::HTTP_OK);	

    }

    function getProductList_post()
    {
    	extract($this->input->post());
		if(!isset($user_id) || $user_id == "" || !isset($brand_id) || $brand_id == "" || $country_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Params'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $brands = array();
        $products = array();

        if(!isset($brand_page) || $brand_page <= 0)
        {
            $brand_page = 1;
        }

        if(!isset($product_page) || $product_page <= 0)
        {
            $product_page = 1;
        }

        $condition_brand = "";
        $condition_proudct = "";
        if(isset($search) && $search != '')
        {
            $condition_brand = "AND (tbl_businesses.name like '%".$search."%' or tbl_businesses.username like '%".$search."%')";

            $condition_proudct = "AND (tbl_gifticons.name like '%".$search."%')";    
        }

        $condition = "";

        $this->db->select("tbl_businesses.*,tbl_business_country.gift_country_id");
		$this->db->from('tbl_businesses');
		$this->db->join('tbl_business_country',"tbl_business_country.business_id= tbl_businesses.id ",'left');
		if(isset($tag_id) && $tag_id != '')
		{
			$condition = "AND tbl_business_tags.tag_id = ".$tag_id." ";
			$this->db->join('tbl_business_tags',"tbl_business_tags.business_id= tbl_businesses.id ",'left');
		}
		$this->db->where("tbl_businesses.is_active = 1 AND tbl_businesses.is_delete = 0 AND tbl_business_country.gift_country_id = ".$country_id." ".$condition_brand." ".$condition." ");
        $this->db->group_by("tbl_businesses.id");
        $this->db->order_by("tbl_businesses.sequence ASC");
        $this->db->limit($this->per_page, (($brand_page-1) * $this->per_page));
		$query = $this->db->get();
		if($query->num_rows() >= 1)
		{
			$brands = $query->result_array();
			foreach ($brands as $mkey => $mvalue) 
			{
				if($mvalue['image'] != '')
				{
					$brands[$mkey]['image'] = BRAND_IMAGE.$mvalue['image'];
				}
			}
		}

		$this->db->select("tbl_gifticons.*,tbl_businesses.id as business_id");
        $this->db->from('tbl_gifticons');
        $this->db->join('tbl_businesses',"tbl_gifticons.business_id= tbl_businesses.id ",'left');
        if(isset($tag_id) && $tag_id != '')
        {
            //$condition = "AND tbl_gifticons_tags.tag_id = ".$tag_id." ";
            $this->db->join('tbl_gifticons_tags',"tbl_gifticons_tags.gifticon_id= tbl_gifticons.id ",'left');
            $this->db->where("tbl_gifticons_tags.tag_id = '".$tag_id."' ");
        }
        $this->db->where(" tbl_gifticons.business_id = '".$brand_id."' AND tbl_gifticons.is_active = 1 AND tbl_businesses.is_active = 1 AND tbl_gifticons.is_delete = 0 AND tbl_gifticons.sale_end_date >= '".date('Y-m-d')."' ".$condition_proudct." ");
        $this->db->order_by("tbl_gifticons.sequence ASC");
        $this->db->limit($this->per_page, (($product_page-1) * $this->per_page));
        $query = $this->db->get();
        //exchange rate
        $exchange_rate = $this->db->get_where('tbl_exchange_rate',array('ex_country'=>'KRW'))->row_array();
        //exchange rate
        if($query->num_rows() >= 1)
        {
            $products = $query->result_array();
            foreach ($products as $pkey => $pvalue) 
            {  
                if (!empty($pvalue['wincube_image'])) {
                    $products[$pkey]['image'] = $pvalue['wincube_image'];
                }
                else if ($pvalue['image'] != '')
                {
                    $products[$pkey]['image'] = GIFT_IMAGE.$pvalue['image'];
                }

                $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$pvalue['id']))->result_array();
            
                $products[$pkey]['gift_size'] = $gift_size;

                $products[$pkey]['qry_remaining'] = $this->User_model->qtyRemaing($pvalue['id']);

                $products[$pkey]['business'] = $this->User_model->get_business($pvalue['business_id']);

                if($pvalue['wincube_id'] != null && isset($isWon) && $isWon == "false")
                {
                    $products[$pkey]['normal_price'] = round((double)$pvalue['normal_price'] / (double)$exchange_rate['rate'], 2);
                    $products[$pkey]['coupon_price'] = round((double)$pvalue['coupon_price'] / (double)$exchange_rate['rate'], 2);
                }
            }
		}

		$responsedata = array("brands"=>$brands,"products"=>$products);

		$message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$responsedata];
        $this->response($message, REST_Controller::HTTP_OK);	    
	}

    function qrScan_post()
    {
    	extract($this->input->post());
		if(!isset($gifticon_id) || $gifticon_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $current_date = date('Y-m-d');

        $this->db->select("tbl_gifticons.*,tbl_businesses.*");
		$this->db->from('tbl_gifticons');
		$this->db->join('tbl_businesses',"tbl_businesses.business_id= tbl_gifticons.id ",'left');
		$this->db->where("tbl_gifticons.is_active = 1 AND tbl_gifticons.is_delete = 0 ");
		$query = $this->db->get();
		if($query->num_rows() >= 1)
		{
			$brands = $query->result_array();
			foreach ($brands as $mkey => $mvalue) 
			{
				if($mvalue['image'] != '')
				{
					$brands[$mkey]['image'] = GIFT_IMAGE.$mvalue['image'];
				}
			}

			$message = ['code' => '1','message' => $this->lang->line("data_found"),"data"=>$brands];
            $this->response($message, REST_Controller::HTTP_OK);
		}else{

			$message = ['code' => '0','message' => $this->lang->line("invalid_qr")];
            $this->response($message, REST_Controller::HTTP_OK);  
		}
	}


	function makeFriend_post()
	{
		extract($this->input->post());
		if(!isset($user_id) || $user_id == "" || !isset($friend_id) || $friend_id == "" )
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $this->db->select("*");
		$this->db->from('tbl_friend');
		$this->db->where(" (tbl_friend.user_id = '".$user_id."' AND tbl_friend.friend_id = '".$friend_id."') OR (tbl_friend.user_id = '".$friend_id."' AND tbl_friend.friend_id = '".$user_id."') ");
		$query = $this->db->get();
		if($query->num_rows() >= 1)
		{
			$message = ['code' => '0','message' =>$this->lang->line("already_friend")];
            $this->response($message, REST_Controller::HTTP_OK);
		}
		else
		{
			$this->db->insert('tbl_friend',array('user_id'=>$user_id,'friend_id'=>$friend_id));

			$message = ['code' => '1','message' => $this->lang->line("friends")];
            $this->response($message, REST_Controller::HTTP_OK);
		}
	}

	function getFriends_post()
	{
		extract($this->input->post());
		if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        if(!isset($page) || $page <= 0)
        {
            $page = 1;
        }

        $this->db->select("*");
		$this->db->from('tbl_friend');
		$this->db->where(" (tbl_friend.user_id = '".$user_id."' OR tbl_friend.friend_id = '".$user_id."') LIMIT ".(($page-1) * $this->per_page).",".$this->per_page." ");
		$query = $this->db->get();
		if($query->num_rows() >= 1)
		{
			$friends = $query->result_array();
            foreach ($friends as $key => $value) 
            {
                if($value['user_id'] == $user_id)
                {
                    $frndid = $value['friend_id'];
                }else{
                    $frndid = $value['user_id'];
                }

                $fri_data = $this->User_model->get_user($frndid);

                if($fri_data == !null)
                {
                    $friends[$key]['friend_data'] = $fri_data;
                }else{
                    $friends[$key]['friend_data'] = null;
                }
            }

            $message = ['code' => '1','message' => $this->lang->line("data_found"),'data'=>(array)$friends];
            $this->response($message, REST_Controller::HTTP_OK);

        }
		else
		{
			$message = ['code' => '2','message' => $this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
		}
	}

    function searchFriend_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $keyword == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $friendsids_array = array();

        if(!isset($page) || $page <= 0)
        {
            $page = 1;
        }

        $condition = "";
        $this->db->select("*");
        $this->db->from('tbl_friend');
        $this->db->where(" (tbl_friend.user_id = '".$user_id."' OR tbl_friend.friend_id = '".$user_id."') ");
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $friends = $query->result_array();
            foreach ($friends as $key => $value) 
            {
                if($value['user_id'] == $user_id)
                {
                    $frndid = $value['friend_id'];
                }else{
                    $frndid = $value['user_id'];
                }
                $friendsids_array[] = $frndid; 
            }

            $friendsids = implode(',', $friendsids_array);
            $condition = " AND id NOT IN (".$friendsids.") ";
        }

        $this->db->select("*");
        $this->db->from('tbl_user');
        $this->db->where(" (tbl_user.name like '%".$keyword."%' OR tbl_user.username like '%".$keyword."%') AND tbl_user.is_active = 1 AND tbl_user.is_delete = 0 ".$condition." AND id != '".$user_id."' LIMIT ".(($page-1) * $this->per_page).",".$this->per_page." ");
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $userdata = $query->result_array();

            foreach ($userdata as $key => $value) {
                $userdata[$key]['profile_image'] = PROFILE_IMAGE.$value['profile_image'];
            }
            
            $message = ['code' => '1','message' => $this->lang->line("data_found"),'data'=>$userdata];
            $this->response($message, REST_Controller::HTTP_OK);
        }
        else
        {
            $message = ['code' => '2','message' => $this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function removeFriend_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $friend_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $this->db->delete('tbl_friend',array('user_id'=>$user_id,'friend_id'=>$friend_id));
        $this->db->delete('tbl_friend',array('user_id'=>$friend_id,'friend_id'=>$user_id));

        $message = ['code' => '1','message' => $this->lang->line("friend_remove")];
        $this->response($message, REST_Controller::HTTP_OK);
    }

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
        //exchange rate
        $exchange_rate = $this->db->get_where('tbl_exchange_rate',array('ex_country'=>'KRW'))->row_array();
        //exchange rate
        if($query->num_rows() >= 1)
        {
            $products = $query->row_array();
            $products['image'] = empty($products['wincube_image'])
                ? GIFT_IMAGE.$products['image']
                : $products['wincube_image'];
            $products['business'] = $this->User_model->get_business($products['business_id']);
            
	        $this->db->select("tbl_gifticons_tags.*,tbl_tags.image");
	        $this->db->from('tbl_gifticons_tags');
	        $this->db->from('tbl_tags');
	        $this->db->where(" tbl_gifticons_tags.tag_id = tbl_tags.id AND tbl_gifticons_tags.gifticon_id = '".$products['id']."' ");
	        $query = $this->db->get();
	        $tag = $query->row_array();
            if (empty($tag)) {
                $tag = $this->db->get_where('tbl_tags', ['id' => 4])->row_array();
            }

            $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$product_id))->result_array();
            
            $products['gift_size'] = $gift_size;

	        $products['tag_image'] = BRAND_IMAGE.$tag['image'];

            $qty = $this->User_model->qtyRemaing($product_id);

            if($qty > 5)
            {
                $qty = 5;
            }else{
                $qty = $qty;
            }

            $products['qry_remaining'] = $qty;
            // if($products['wincube_id'] != null)
            if($products['wincube_id'] != null && isset($isWon) && $isWon == "false")
            {
                $products['normal_price'] = round((double)$products['normal_price'] / (double)$exchange_rate['rate'], 2);
                $products['coupon_price'] = round((double)$products['coupon_price'] / (double)$exchange_rate['rate'], 2);
            }

            $product_images = $this->db->get_where('tbl_gifticon_images',array('gifticon_id'=>$product_id))->result_array();

            foreach ($product_images as $key => $value) {
                $product_images[$key]['image'] = GIFT_IMAGE.$value['image'];
            }

            if($product_images)
            {
                $products['product_images'] =  $product_images;
            }else{
                $products['product_images'] = [['image' => $products['image']]];
            }

            $message = ['code' => '1','message' => $this->lang->line("data_found"),'data'=>$products];
        	$this->response($message, REST_Controller::HTTP_OK);
        }else{
        	$message = ['code' => '2','message' => $this->lang->line("no_data_found")];
        	$this->response($message, REST_Controller::HTTP_OK);
        }
	}

    function purchased_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $stripe_charge_token == "" || $transaction_token == "" || $price == "" || $fees == "" || $total_price == "" || $metadata == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $allpostdata = $this->input->post();
        $mdata = $allpostdata['metadata'];
        //unset($allpostdata['metadata']);
        unset($allpostdata['coupon_code']);
        if(isset($allpostdata['currency']))
        {
            unset($allpostdata['currency']);
        }
        $this->db->insert('tbl_transaction',$allpostdata);
        $transaction_id = $this->db->insert_id();
        unset($allpostdata['coupon_discount_amount']);
        unset($allpostdata['metadata']);
        $pids_array = array();
        $voucher_issue_results = [];

        $currentdate = date('Y-m-d');

        if($this->common->isJson($mdata))
        {
            $point = 0;
            $loop_data = json_decode($mdata,true);
            
            foreach ($loop_data as $key => $loopvalue) {
                for ($i=1; $i <= $loopvalue['qty'] ; $i++) { 
                    $giftdetails = $this->User_model->getGift($loopvalue['gift_id']);

                    /*if($loopvalue['individual_price'] >= 12)
                    {
                        $point = $point + 1;      
                    }*/

                    if($giftdetails['expiration_type'] == 0)
                    {
                        $expdate = date('Y-m-d', strtotime($giftdetails['expiry_date'])).' '.date('H:i'); //valid_end_date
                    }else if($giftdetails['expiration_type'] == 1)
                    {
                        $expdate = date('Y-m-d H:i', strtotime("+3 months", strtotime(date('Y-m-d H:i')))); //valid_end_date
                    }else if($giftdetails['expiration_type'] == 2)
                    {
                        $expdate = date('Y-m-d H:i', strtotime("+6 months", strtotime(date('Y-m-d H:i')))); //valid_end_date
                    }else if($giftdetails['expiration_type'] == 3)
                    {
                        $expdate = date('Y-m-d H:i', strtotime("+12 months", strtotime(date('Y-m-d H:i')))); //valid_end_date
                    }else{
                        $expdate = $giftdetails['expiry_date'];
                    }
        
                    $param = array(
                        "stripe_charge_token"=>$stripe_charge_token,
                        "transaction_id"=>$transaction_id,
                        "user_id"=>$user_id,
                        "gifticon_id"=>$loopvalue['gift_id'],
                        "business_id"=>$giftdetails['business_id'],
                        "gifticon_type"=>$giftdetails['gifticon_type'],
                        "giftcard_format"=>$giftdetails['giftcard_format'],
                        "plain_code"=>$giftdetails['plain_code'],
                        "pin"=>$giftdetails['pin'],
                        "price"=>$loopvalue['individual_price'],
                        "normal_price"=>$loopvalue['individual_price'],
                        "purchase_date"=>date("Y-m-d H:i"),
                        "expiry_date"=>$expdate,
                        "qty"=>$loopvalue['qty']
                    );

                    if(isset($currency) && $currency != "")
                    {
                       $param['currency'] = $currency;
                    }

                    if(isset($loopvalue['size']) && $loopvalue['size'] != "")
                    {
                        $param['size'] = $loopvalue['size'];
                    }

                    if(isset($coupon_code) && $coupon_code != "")
                    {
                        $param['coupon_code'] = $coupon_code;
                    }

                    if(isset($coupon_discount_amount) && $coupon_discount_amount != "")
                    {
                        $param['coupon_discount_amount'] = $coupon_discount_amount;
                    }

                    if(isset($giftdetails['giftcard_id']) && $giftdetails['giftcard_id'] != '')
                    {
                        $param['giftcard_id'] = $giftdetails['giftcard_id'];
                    }
                    // $param['currency'] = 'usds';
                    // here i need to update moveto

                   // $param['move_to'] = 'mygiftbox';

                    $param['purchaser'] = 'mygiftbox';

                    $this->db->insert('tbl_purchases',$param);
                    $p_id = $this->db->insert_id();
                    $pids_array[] = $this->db->insert_id();

                    if($giftdetails['gifticon_type'] == 0)
                    {
                        $qdata = "gifticon_id=".$loopvalue['gift_id']."&giftdetails=".$giftdetails['name']."&user_id=".$user_id."&purchase_id=".$this->db->insert_id()."";

                        /* here i need to do QR code logic */

                            $qrc = 'b_'.$giftdetails['business_id'].'_p_'.$p_id.'_'.strtotime(date("Y-m-d H:i:s"));//$this->RandomString();

                            // $string = strtotime(date("Y-m-d H:i:s")); echo $string;

                            $scannervalue =  $this->qrGenerator([
                                'code' => $qrc,     // The unique code generate by your system. NOTE length <= 32
                                'mid' => 11748, // PARTNER_ID
                                'sign_type' => 'MD5',
                            ]);

                        $QRdata = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=".$scannervalue.base64_encode($qdata)."";

                    }else{

                        if($giftdetails['giftcard_format'] == 2)
                        {
                            $qdata1 = "".$loopvalue['gift_id']."_".$user_id."_".$p_id."";
                                
                        }

                        $this->db->update('tbl_giftcards',array('is_used'=>1),array('gifticon_id'=>$loopvalue['gift_id'],'code'=>$giftdetails['plain_code']));
                    }

                    $datatobeupdate = array();

                    if(isset($QRdata) && $QRdata != "")
                    {
                        $datatobeupdate['qr_code'] = $QRdata;
                    }
                    
                    if(isset($qdata1) && $qdata1 != "")
                    {
                        $datatobeupdate['barcode_string'] = $giftdetails['plain_code'];
                        $datatobeupdate['barcode_string_ids'] = $qdata1;
                    }

                    if(!empty($datatobeupdate))
                    {
                        $this->db->update('tbl_purchases',$datatobeupdate,array('id'=>$p_id));
                    }

                    // Call WinCube voucher-issuing API
                    if (!empty($giftdetails['wincube_id'])) {

                        $user = $this->db->get_where('tbl_user', ['id' => $user_id])->row_array();
                        $phone_number = $allpostdata['phone'] ?? $user['phone'];
                        $client = new GuzzleHttp\Client();
                        $payload = [
                            'query' => [
                                'mdcode' => 'gifticon_nz',
                                'response_type' => 'JSON',
                                'msg' => mb_convert_encoding('해외에서 마음이 담긴 선물이 도착했습니다! 사랑이 담긴 선물을 확인해주세요 :D [Global Gifticon : 글로벌 선물하기 서비스]', 'EUC-KR', 'UTF-8'),
                                'title' => mb_convert_encoding('선물과 함께 예쁜 하루 보내세요. 먼 곳에서도 응원할게요', 'EUC-KR', 'UTF-8'),
                                'callback' => $phone_number,
                                'goods_id' => $giftdetails['wincube_id'],
                                'phone_no' => $phone_number,
                                'tr_id' => $p_id
                            ]
                        ];
                        log_message('debug', '>>> Sending request to WinCube:');
                        log_message('debug', json_encode($payload, JSON_PRETTY_PRINT));
                        $res = $client->request('POST', WINCUBE_API_BASE . 'request.do', $payload);
                        $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
                        log_message('debug', '>>> Got response from WinCube:');
                        log_message('debug', $body);
                        $voucher_issue_result = json_decode($body, true);
                        if ($voucher_issue_result['result_code'] == 1000) { // Success
                            $ctr_id = $voucher_issue_result['ctr_id'];
                            $this->db->update('tbl_purchases', ['wincube_ctr_id' => $ctr_id], ['id' => $p_id]);
                        }
                        $response_reason = $voucher_issue_result['result_code'];
                        $this->db->update('tbl_purchases', ['response_reason' => $response_reason], ['id' => $p_id]);

                        $voucher_issue_results[] = $voucher_issue_result;

                    }
                }
            }

            $pids = implode(',', $pids_array);

            // here i need to update reward point if total price is more than 12$

            /*if($point != 0)
            {*/
                $updpoint = intval($allpostdata['total_price'] / 12);

                $updcoin = "UPDATE tbl_user set reward_points = reward_points + ".$updpoint." where id = '".$user_id."' ";
                $this->db->query($updcoin); 
            //}                   

            //$this->db->update('tbl_transaction',array('qty'=>))

            $respns = [
                'purchase_ids' => $pids,
                'voucher_issue_results' => $voucher_issue_results
            ];

            $NotiMessage = 'Please check your receipt';

            $this->db->insert('tbl_notification', array(
                'title' => $NotiMessage,
                'user_id' => $user_id,
                'user_type' => 'U',
                'message' => $NotiMessage,
                'tag' => 'receipt',
                'type' => 'receipt',
                'purchase_id' => $pids,
                'provider' => empty($voucher_issue_results) ? null : 'wincube'
            ));

            $UserDetails = $this->db->get_where('tbl_user',array('id'=>$user_id))->row_array();

            $msgpush = array("body"=>$NotiMessage,'title'=>$NotiMessage,"tag"=>'receipt',"type"=>'receipt', 'currency' => isset($currency) ? $currency : NULL);

            $body = array(); 
            $bodyI['aps'] = array('sound'=>'default', 'alert' => array('title'=>'Receipt','body'=>$NotiMessage),"tag"=>'receipt', 'currency' => isset($currency) ? $currency : NULL);

            if($UserDetails['device_type']=='A' && $UserDetails['device_id']!='')
            {
                $registatoin_ids_D = $UserDetails['device_id'];
                $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
            }

            if($UserDetails['device_type']=='I' && $UserDetails['device_id']!='')
            {
                $this->common->send_notification_ios_customer($bodyI,$UserDetails['device_id']);
            }

            $message = ['code' => '1','message' => $this->lang->line("purchased"),'data'=>$respns];
            $this->response($message, REST_Controller::HTTP_OK);

        }else{
            $message = ['code' => '0','message' => "Invalid json format"];
            $this->response($message, REST_Controller::HTTP_OK);
        }    
    }

            function qrGenerator($params)
            {
                $params = [
                    'prefix' => 'ipc:',
                    'pid' => '11748' . '0',
                    'code' => $params['code'],
                    'sign_type' => $params['sign_type'],
                    'suffix' => ':cpi'
                ];
                return implode("|", $params);
            }

            function RandomString($length = 16)
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }

    function purchasedDetail_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $purchase_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

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

            $message = ['code' => '1','message' => $this->lang->line("data_found"),'data'=>$purchaseddata];
            $this->response($message, REST_Controller::HTTP_OK);

        }else{
            $message = ['code' => '2','message' => $this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }


    function occasionList_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $allpostdata = $this->input->post();

        $occasion = $this->db->get_where('tbl_occasions',array('is_active'=>1))->result_array();

        if($occasion)
        {
        	foreach ($occasion as $key => $value) {
        		$occasion[$key]['image'] = OCCASION_IMAGE.$value['image'];
        	}
			$message = ['code' => '1','message' => $this->lang->line("data_found"),'data'=>$occasion];
        	$this->response($message, REST_Controller::HTTP_OK);

        }else{

        	$message = ['code' => '2','message' => $this->lang->line("no_data_found")];
        	$this->response($message, REST_Controller::HTTP_OK);	
        }
	}

	function occasionProducts_post()
	{
		extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $occasion_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

		$brands = array();
        $products = array();

        if(!isset($brand_page) || $brand_page <= 0)
        {
            $brand_page = 1;
        }

        if(!isset($product_page) || $product_page <= 0)
        {
            $product_page = 1;
        }

        $condition_brand = "";
        $condition_proudct = "";
        if(isset($search) && $search != '')
        {
            $condition_brand = "AND (tbl_businesses.name like '%".$search."%' or tbl_businesses.username like '%".$search."%')";

            $condition_proudct = "AND (tbl_gifticons.name like '%".$search."%')";    
        }

        $condition = "";

        $this->db->select("tbl_businesses.*,tbl_business_occasion.business_id");
		$this->db->from('tbl_businesses');
		$this->db->join('tbl_business_occasion',"tbl_business_occasion.business_id= tbl_businesses.id ",'left');
		if(isset($tag_id) && $tag_id != '')
		{
			$condition = "AND tbl_business_tags.id = ".$tag_id." ";
			$this->db->join('tbl_business_tags',"tbl_business_tags.business_id= tbl_businesses.id ",'left');
		}
		$this->db->where("tbl_businesses.is_active = 1 AND tbl_businesses.is_delete = 0 AND tbl_business_occasion.occasion_id = ".$occasion_id." ".$condition_brand." ");
        $this->db->order_by("tbl_businesses.sequence ASC");
        $this->db->limit($this->per_page, (($brand_page-1) * $this->per_page));
		$query = $this->db->get();
		if($query->num_rows() >= 1)
		{
			$brands = $query->result_array();
			foreach ($brands as $mkey => $mvalue) 
			{
				if($mvalue['image'] != '')
				{
					$brands[$mkey]['image'] = BRAND_IMAGE.$mvalue['image'];
				}
			}
		}
        $cnd = "";
        if(!empty($brands))
        {
            if(isset($brand_id) && $brand_id != '')
            {
                $brand_id = $brand_id;
                $cnd = " AND tbl_gifticons.business_id = '".$brand_id."' ";
                }else{

                $brand_id = $brands[0]['id'];
                $cnd = " AND tbl_gifticons.business_id = '".$brand_id."' ";
            }    
        }else{

            if(isset($brand_id) && $brand_id != '')
            {
                $brand_id = $brand_id;
                $cnd = " AND tbl_gifticons.business_id = '".$brand_id."' ";
            }
        }

		$this->db->select("tbl_gifticons.*,tbl_businesses.id as business_id");
        $this->db->from('tbl_gifticons');
        $this->db->join('tbl_businesses',"tbl_gifticons.business_id= tbl_businesses.id ",'left');
        if(isset($tag_id) && $tag_id != '')
        {
            //$condition = "AND tbl_gifticons_tags.tag_id = ".$tag_id." ";
            $this->db->join('tbl_gifticons_tags',"tbl_gifticons_tags.tag_id= tbl_gifticons.id ",'left');
        }

        $this->db->where(" tbl_gifticons.is_active = 1 AND tbl_businesses.is_active = 1 AND tbl_gifticons.sale_end_date >= '".date('Y-m-d')."' AND tbl_gifticons.is_delete = 0 ".$condition_proudct." ".$cnd." ");
        $this->db->order_by("tbl_gifticons.sequence ASC");
        $this->db->limit($this->per_page, (($product_page-1) * $this->per_page));
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $products = $query->result_array();
            foreach ($products as $pkey => $pvalue) 
            {
                if($pvalue['image'] != '')
                {
                    $products[$pkey]['image'] = GIFT_IMAGE.$pvalue['image'];
                }

                $gift_size = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$pvalue['id']))->result_array();
            
                $products[$pkey]['gift_size'] = $gift_size;

                $products[$pkey]['qry_remaining'] = $this->User_model->qtyRemaing($pvalue['id']);

                $products[$pkey]['business'] = $this->User_model->get_business($pvalue['business_id']);
            }
		}

		$responsedata = array("brands"=>$brands,"products"=>$products);

		$message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$responsedata];
        $this->response($message, REST_Controller::HTTP_OK);	   
	}

	function myGiftbox_post()
	{
		extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        if(!isset($page) || $page <= 0)
        {
            $page = 1;
        }

        // update read count

        // here i need to call an function to remove from used send data.
        $condition = "";
        $removeIDS = $this->User_model->usedsendNOTIN($user_id);
        if($removeIDS != '')
        {
            $condition = " `id` NOT IN (".$removeIDS.") AND ";
        }

        $updcoin = "UPDATE tbl_purchases set is_read = 1 where ".$condition." ((user_id = '".$user_id."' OR `giftto_user_id` = '".$user_id."') AND `is_redeem` = 0 AND gift_from_user_id != '".$user_id."' ) OR (id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'mygiftbox') ) ";
        $this->db->query($updcoin); 

        $updcoin1 = "UPDATE tbl_notification set is_read_home = 1 where user_id = '".$user_id."' AND user_type = 'U' AND type IN ('gift','receipt') ";
        $this->db->query($updcoin1);

        $this->db->select("tbl_purchases.*,tbl_purchases.id as purchase_id");
        $this->db->from('tbl_purchases');
        $this->db->where("  ".$condition." ((user_id = '".$user_id."' OR `giftto_user_id` = '".$user_id."') AND `is_redeem` = 0 AND gift_from_user_id != '".$user_id."' ) OR (id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'mygiftbox') ) ORDER BY last_update desc LIMIT ".(($page-1) * $this->per_page).",".$this->per_page." ");    
        $query = $this->db->get();
		if($query->num_rows() >= 1)
		{
            $pid = array();
			$purchase = $query->result_array();

            $client = new GuzzleHttp\Client();
            // Check and update WinCube vouchers
            foreach ($purchase as $k => $v) {
                $item = $purchase[$k];
                if (!empty($item['wincube_ctr_id'])) {
                    $res = $client->request('POST', WINCUBE_API_BASE . 'coupon_status.do', [
                        'query' => [
                            'mdcode' => 'gifticon_nz',
                            'response_type' => 'JSON',
                            'tr_id' => $item['id']
                        ]
                    ]);
                    $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
                    $status = json_decode($body, true);
                    if ($status['StatusCode'] == 4006) {
                        $this->db->update('tbl_purchases', [
                            'is_redeem' => 1,
                            'redeem_date' => date_format(date_create($status['SwapDt']), 'Y-m-d H:i:s')
                        ], ['id' => $item['id']]);
                    }
                    // unset($purchase[$k]);
                }
                $isKItem = $this->db->get_where('tbl_gifticons',array('id'=>$purchase[$k]['gifticon_id']))->row_array();

                if(!empty($isKItem['wincube_id']))
                {
                    unset($purchase[$k]);
                }
            }
            $purchase = array_values($purchase);

            foreach ($purchase as $key => $value) {

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
        
            $message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$purchase];
        	$this->response($message, REST_Controller::HTTP_OK);	 

		}else{

			$message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
        	$this->response($message, REST_Controller::HTTP_OK);
		}
	}

	function usedSentbox_post()
	{
		extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        if(!isset($page) || $page <= 0)
        {
            $page = 1;
        }

        $condition = "";
        $removeIDS = $this->User_model->mygiftNOTIN($user_id);

        if($removeIDS != '')
        {
            $condition = " `id` NOT IN (".$removeIDS.") AND ";
	    }

        $this->db->select("tbl_purchases.*,tbl_purchases.id as purchase_id");
		$this->db->from('tbl_purchases'); 
		
        $this->db->where(" ".$condition." ( (user_id = '".$user_id."' AND `is_redeem` = 1) OR (`giftto_user_id` = '".$user_id."' AND `is_redeem` = 0) OR (user_id = '".$user_id."' OR `gift_from_user_id` = '".$user_id."') OR (`giftto_user_id` = '".$user_id."' AND `is_redeem` = 1 ) OR ( `gift_from_user_id` = '".$user_id."' OR  user_id = '".$user_id."' AND `is_redeem` = 0) ) OR ( id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'used_sent') )  ORDER BY last_update desc LIMIT ".(($page-1) * $this->per_page).",".$this->per_page."  ");

        //$this->db->where(" (user_id = '".$user_id."' AND `is_redeem` = 1) OR (`giftto_user_id` = '".$user_id."' AND `is_redeem` = 1 ) OR ( `gift_from_user_id` = '".$user_id."' ) AND (purchaser = 'used_sent' OR receiver = 'used_sent') AND (move_to IS NOT NULL OR move_to = 'used_sent' )  ORDER BY last_update desc LIMIT ".(($page-1) * $this->per_page).",".$this->per_page."  "); 

		$query = $this->db->get();
		if($query->num_rows() >= 1) 
		{
			$purchase = $query->result_array();

             foreach($purchase as $k => $v)
             {
                 $isKItem = $this->db->get_where('tbl_gifticons',array('id'=>$purchase[$k]['gifticon_id']))->row_array();

                 if($isKItem['wincube_id'] == NULL)
                 {
                    if($purchase[$k]['is_redeem'] == 0 && ($purchase[$k]['giftto_user_id'] == $user_id && $purchase[$k]['move_to'] != 'used_sent'))
                    {
                       unset($purchase[$k]);
		            } else if ($purchase[$k]['user_id'] == $user_id && $purchase[$k]['giftto_user_id'] == 0 && $purchase[$k]['gift_from_user_id'] == 0 && $purchase[$k]['move_to'] != 'used_sent')
                    {
                        unset($purchase[$k]);
                    }
                }
	        }

		    $purchase = array_values($purchase);

			foreach ($purchase as $key => $value) {
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
                    $userdetail = $this->User_model->get_user($value['giftto_user_id']);
                    $purchase[$key]['sent_to'] = $userdetail['username'];
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

            $message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$purchase];
        	$this->response($message, REST_Controller::HTTP_OK);	 

		}else{

			$message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
        	$this->response($message, REST_Controller::HTTP_OK);
		}
	}

	function editName_post()
	{
		extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $purchase_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $this->db->update('tbl_purchases',array('edited_name'=>$edited_name),array('id'=>$purchase_id));

        $message = ['code' => '1','message' =>$this->lang->line("name_update")];
        $this->response($message, REST_Controller::HTTP_OK);
	}

	function sentGift_post()
	{
		extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $purchase_id == "" || $gifted_date == "" || $gift_from_user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $Allpostdata = $this->input->post();
        $p_id = $purchase_id;
        $Allpostdata['purchaser'] = 'used_sent';
        $Allpostdata['receiver'] = 'mygiftbox';

        // $Allpostdata['move_to'] = 'used_sent';

        /*if(!isset($giftto_user_id) || $giftto_user_id == '')
        {*/
           
        /*}*/
        /*if(isset($giftto_user_id) && $giftto_user_id != '')
        {
            $Allpostdata['gifted'] = 1;
        }*/
        unset($Allpostdata['purchase_id']);
        unset($Allpostdata['user_id']);

        $Allpostdata['gifted_date'] = date('Y-m-d H:i');

        $this->db->update('tbl_purchases',$Allpostdata,array('id'=>$p_id));

        $pdetail = $this->User_model->purchasedetail($gift_from_user_id,$p_id);

        $udetail = $this->User_model->get_user($gift_from_user_id);

        $title = 'You Received a gift!';

        if(isset($giftto_user_id) && $giftto_user_id != '')
        {
            $NotiMessage = 'You Received a gift ('.$pdetail['giftdetails']['name'].' $'.$pdetail['price'].' voucher) from '.$udetail['username'].'';

            $this->db->insert('tbl_notification', array(
                'title' => $title,
                'user_id' => $giftto_user_id,
                'user_type' => 'U',
                'message' => $NotiMessage,
                'tag' => 'gift',
                'type' => 'gift',
                'sender_id' => $gift_from_user_id,
                'purchase_id' => $p_id,
                'gifticon_id' => $pdetail['giftdetails']['id'],
                'provider' => empty($pdetail['wincube_ctr_id']) ? null : 'wincube'
            ));

            $UserDetails = $this->db->get_where('tbl_user',array('id'=>$giftto_user_id))->row_array();

            $msgpush = array("body"=>$NotiMessage,'title'=>$title,"tag"=>'gift',"type"=>'gift');

            $body = array(); 
            $bodyI['aps'] = array('sound'=>'default','alert' => array('title'=>$title,'body'=>$NotiMessage),"tag"=>'gift');

            if($UserDetails['device_type']=='A' && $UserDetails['device_id']!='')
            {
            $registatoin_ids_D = $UserDetails['device_id'];
            $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
            }

            if($UserDetails['device_type']=='I' && $UserDetails['device_id']!='')
            {
            $this->common->send_notification_ios_customer($bodyI,$UserDetails['device_id']);
            }
        }

        

        $message = ['code' => '1','message' =>$this->lang->line("gift_sent")];
        $this->response($message, REST_Controller::HTTP_OK);
	}

    function sentGiftThem_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $purchase_id == "" || $gifted_date == ""  || $gift_from_user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $Allpostdata = $this->input->post();
        $p_id = $purchase_id;
        $Allpostdata['purchaser'] = 'used_sent';
        $Allpostdata['receiver'] = 'mygiftbox';

        //$Allpostdata['move_to'] = 'used_sent';
        /*if(!isset($giftto_user_id) || $giftto_user_id == '')
        {*/
            
        /*}*/
        /*if(isset($giftto_user_id) && $giftto_user_id != '')
        {
            $Allpostdata['gifted'] = 1;
        } */   
        unset($Allpostdata['purchase_id']);
        unset($Allpostdata['user_id']);

        $purchased = explode(',', $p_id);

        foreach ($purchased as $key => $value) {

            $p_id = $value;

            $pdetail = $this->User_model->purchasedetail($gift_from_user_id,$p_id);

            $udetail = $this->User_model->get_user($gift_from_user_id);

            $title = 'You Received a gift!';

             if(isset($giftto_user_id) && $giftto_user_id != '')
            {

                $NotiMessage = 'You Received a gift ('.$pdetail['giftdetails']['name'].' $'.$pdetail['price'].' voucher) from '.$udetail['username'].'';

                $this->db->insert('tbl_notification', array(
                    'title' => $title,
                    'user_id' => $giftto_user_id,
                    'user_type' => 'U',
                    'message' => $NotiMessage,
                    'tag' => 'gift',
                    'type' => 'gift',
                    'sender_id' => $gift_from_user_id,
                    'purchase_id' => $p_id,
                    'gifticon_id' => $pdetail['giftdetails']['id'],
                    'provider' => empty($pdetail['wincube_ctr_id']) ? null : 'wincube'
                ));

                $UserDetails = $this->db->get_where('tbl_user',array('id'=>$giftto_user_id))->row_array();

                $msgpush = array("body"=>$NotiMessage,'title'=>$title,"tag"=>'gift',"type"=>'gift');

                $body = array(); 
                $bodyI['aps'] = array('sound'=>'default', 'alert' => array('title'=>$title,'body'=>$NotiMessage),"tag"=>'gift');

                if($UserDetails['device_type']=='A' && $UserDetails['device_id']!='')
                {
                $registatoin_ids_D = $UserDetails['device_id'];
                $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                }

                if($UserDetails['device_type']=='I' && $UserDetails['device_id']!='')
                {
                $this->common->send_notification_ios_customer($bodyI,$UserDetails['device_id']);
                }
            }

            $this->db->update('tbl_purchases',$Allpostdata,array('id'=>$value));    
        }
        
        $message = ['code' => '1','message' =>$this->lang->line("gift_sent")];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    function getHomecount_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        ini_set('memory_limit', '-1');
 
        $data = array();

        $totalgifticons = $this->db->get_where('tbl_gifticons',array('is_delete'=>0))->num_rows();

        $totalGiftshop = $this->db->get_where('tbl_businesses',array('is_delete'=>0))->num_rows();

        $notification_count = $this->db->get_where('tbl_notification',array('user_id'=>$user_id,'is_read'=>0))->num_rows();

        $totalRewardPoint = array('reward_points' => 0);

        $condition = "";
        $removeIDS = $this->User_model->usedsendNOTIN($user_id);
        if($removeIDS != '')
        {
            $condition = " `id` NOT IN (".$removeIDS.") AND ";
        }

        if($user_id != 0)
        {
            $this->db->select("tbl_purchases.*");
            $this->db->from('tbl_purchases');
            $this->db->where(" ".$condition." ((user_id = '".$user_id."' OR `giftto_user_id` = '".$user_id."') AND `is_redeem` = 0 AND gift_from_user_id != '".$user_id."' ) OR (id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'mygiftbox') ) ");
            $query = $this->db->get();
            $totalMygift = $query->num_rows();

            $totalRewardPoint = $this->db->get_where('tbl_user',array('id'=>$user_id))->row_array();

            //$this->db->get_where('tbl_notification',array('user_id'=>$user_id,'user_type'=>'U','type'))

            /*$this->db->select("tbl_purchases.*");
            $this->db->from('tbl_purchases');
            $this->db->where(" ".$condition." (is_read = 0) AND ((user_id = '".$user_id."' OR `giftto_user_id` = '".$user_id."') AND `is_redeem` = 0 AND gift_from_user_id != '".$user_id."' ) OR (id IN(SELECT purchase_id FROM tbl_purchases_swap WHERE user_id= '".$user_id."' AND move_to = 'mygiftbox') ) AND is_read = 0 "); 
            $query1 = $this->db->get();
            
            $totalMygiftPurchase = $query1->num_rows();*/

            $this->db->select("tbl_notification.*");
            $this->db->from('tbl_notification');
            $this->db->where(" user_id = '".$user_id."' AND user_type = 'U' AND is_read_home = '0' AND type IN ('gift','receipt') "); 
            $query1 = $this->db->get();
            
            $totalMygiftPurchase = $query1->num_rows();
        }else{
            $totalMygift = 0;
            $totalMygiftPurchase = 0;
        }

        

        
        $data['total_giftshop'] = $totalGiftshop;

        $data['total_mygift'] = $totalMygift;
        $data['total_mygift_purchase'] = $totalMygiftPurchase;

        $data['total_gifticons'] = $totalgifticons;

        $data['unread_noti_count'] = $notification_count;

        $data['charge_fees'] = '3.40';

        $data['reward_points'] = $totalRewardPoint['reward_points'];

        $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$data];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    function giftDetail_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $gift_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $giftdetails = $this->User_model->getGift($gift_id);

        $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$giftdetails];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    function applyDiscountCode_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $code == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }


        $this->db->select("*");
        $this->db->from('tbl_coupon_codes');
        $this->db->where(" is_active = 1 AND code = '".$code."' AND ('".date('Y-m-d')."' BETWEEN sdate AND edate) ");
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $data = $query->row_array(); 
            // coupon_code
            $check = $this->db->get_where('tbl_purchases',array('coupon_code'=>$code,'user_id'=>$user_id))->row_array();
            if($check)
            {
                $message = ['code' => '2','message' =>$this->lang->line("coupen_already")];
            $this->response($message, REST_Controller::HTTP_OK);
            }else{
                $message = ['code' => '1','message' =>$this->lang->line("coupen_apply"),'data'=>$data];
                $this->response($message, REST_Controller::HTTP_OK);    
            }
        }else{
            $message = ['code' => '2','message' =>$this->lang->line("coupen_invalid")];
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

        if(!isset($page) || $page <= 0)
        {
            $page = 1;
        }

        $this->db->select("*");
        $this->db->from('tbl_notification');
        $this->db->where(" user_id = '".$user_id."' AND user_type = 'U' order by id desc LIMIT ".(($page-1) * $this->per_page).",".$this->per_page." ");
        $query = $this->db->get();
        $noti = $query->result_array(); 

        //$noti = $this->db->order_by('id','desc')->get_where('tbl_notification',array('user_id'=>$user_id,'user_type'=>'U'))->result_array();
        if($noti)
        {
            $this->db->update('tbl_notification',array('is_read'=>1),array('user_id'=>$user_id,'user_type'=>'U'));
            foreach ($noti as $key => $value) 
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
                if(!empty($purchasedata[0]['transaction']))
                {
                    $noti[$key]['transaction_data'] = $purchasedata[0]['transaction'];    
                    $noti[$key]['currency'] = $purchasedata[0]['currency'];    
                }else{
                    $noti[$key]['transaction_data'] = (object) array();
                }
                
              //  $noti[$key]['purchasedata'] = $purchasedata;
            }

            $message = ['code' => '1','message' =>$this->lang->line("data_found"),'data'=>$noti];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function moveGifticon_post()
    {
        extract($this->input->post());
        if(!isset($purchase_id) || $user_id == "" || $move_to == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $param = array('move_to'=>$move_to);

        $pdata = $this->db->get_where('tbl_purchases',array('id'=>$purchase_id))->row_array();

        $this->db->update('tbl_purchases',$param,array('id'=>$purchase_id));

        $checkalready = $this->db->get_where('tbl_purchases_swap',array('purchase_id'=>$purchase_id,'user_id'=>$user_id))->row_array();
        if($checkalready)
        {
            $this->db->update('tbl_purchases_swap',array('move_to'=>$move_to),array('id'=>$checkalready['id']));
        }else{
            $this->db->insert('tbl_purchases_swap',array('user_id'=>$user_id,'move_to'=>$move_to,'purchase_id'=>$purchase_id));
        }
        
        $message = ['code' => '1','message' =>$this->lang->line("moved_sucess")];
        $this->response($message, REST_Controller::HTTP_OK);  
    }

    function uploadGif_post()
    {
        if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
            $img = $this->common->image_upload("image","./assets/gift/");
        }

        if($img)
        {
            $fullimag = base_url().'assets/gift/'.$img;
            $data = array('img_name'=>$img,'full_image'=>$fullimag);
            $message = ['code' => '1','message' =>'success','data'=>$data];
            $this->response($message, REST_Controller::HTTP_OK);    
        }else{
            $message = ['code' => '0','message' =>'something wrong'];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function importVoucher_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $brand_name == "" || $item_name == "" || $main_image == "" || $crop_image == "")
        {
           // print_r($this->input->post());
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $allPost = $this->input->post();

        $this->db->select("*");
        $this->db->from('tbl_businesses');
        $this->db->where(" (name = '".$brand_name."' OR username = '".$brand_name."') AND is_active = 1 AND is_delete = 0 ");
        $query = $this->db->get();
        $checkband = $query->row_array(); 

        if(!empty($checkband))
        {
            $allPost['business_id'] = $checkband['id'];
        }

        $this->db->select("*");
        $this->db->from('tbl_gifticons');
        $this->db->where(" name = '".$item_name."' AND is_active = 1 AND is_delete = 0 ");
        $query1 = $this->db->get();
        $checkgift = $query1->row_array(); 

        if(!empty($checkgift))
        {
            $allPost['gifticon_id'] = $checkgift['id'];
        }

        $allPost['is_import'] = 1;

        $allPost['move_to'] = 'mygiftbox';
       // $allPost['purchaser'] = 'mygiftbox';
        
        $this->db->insert('tbl_purchases',$allPost);
        
        $message = ['code' => '1','message' =>$this->lang->line("voucher_imported")];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    function RewardList_post()
    {

        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $allPost = $this->input->post();

        if(!isset($page) || $page <= 0)
        {
            $page = 1;
        }

        /*$condition_proudct = "";
        if(isset($search) && $search != '')
        {
            $condition_proudct = "AND (tbl_gifticons.name like '%".$search."%')";    
        }*/

        $condition = "";

        $this->db->select("tbl_reward.*,tbl_reward.id as reward_id,tbl_businesses.id as business_id,tbl_businesses.*");

        $this->db->from('tbl_reward');
        $this->db->join('tbl_businesses',"tbl_reward.business_id= tbl_businesses.id ",'left');
        $this->db->where(" tbl_reward.is_active = 1 AND tbl_businesses.is_active = 1 AND tbl_reward.is_delete = 0 ");
        $this->db->order_by("tbl_reward.id ASC");
        $this->db->limit($this->per_page, (($page-1) * $this->per_page));
        $query = $this->db->get();
        if($query->num_rows() >= 1)
        {
            $reward = $query->result_array();
            foreach ($reward as $rkey => $rvalue) 
            {  
                $countrydetail = $this->db->get_where('tbl_gift_country',array('id'=>$rvalue['country_id']))->row_array();
                $reward[$rkey]['country_name'] = $countrydetail['name'];
                $reward[$rkey]['short_name'] = $countrydetail['short_code'];
                $reward[$rkey]['flag'] = BRAND_IMAGE.$countrydetail['flag'];

                $reward[$rkey]['gifticons'] = $this->User_model->getGift($rvalue['gifticon_id']);
            }

            $message = ['code' => '1','message' =>$this->lang->line("data_found"),"data"=>$reward];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = ['code' => '2','message' =>$this->lang->line("no_data_found")];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    function clainReward_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $reward_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $rewardDetail = $this->db->get_where('tbl_reward',array('id'=>$reward_id))->row_array();
        if($rewardDetail)
        {
            $giftdetails = $this->User_model->getGiftReward($rewardDetail['gifticon_id']);

            if($giftdetails['expiration_type'] == 0)
            {
                $expdate = date('Y-m-d', strtotime($giftdetails['expiry_date'])).' '.date('H:i'); //valid_end_date
            }else if($giftdetails['expiration_type'] == 1)
            {
                $expdate = date('Y-m-d H:i', strtotime("+3 months", strtotime(date('Y-m-d H:i')))); //valid_end_date
            }else if($giftdetails['expiration_type'] == 2)
            {
                $expdate = date('Y-m-d H:i', strtotime("+6 months", strtotime(date('Y-m-d H:i')))); //valid_end_date
            }else if($giftdetails['expiration_type'] == 3)
            {
                $expdate = date('Y-m-d H:i', strtotime("+12 months", strtotime(date('Y-m-d H:i')))); //valid_end_date
            }else{
                $expdate = $giftdetails['expiry_date'];
            }

            $param = array(
                "reward_id"=>$reward_id,
                "user_id"=>$user_id,
                "gifticon_id"=>$rewardDetail['gifticon_id'],
                "business_id"=>$giftdetails['business_id'],
                "gifticon_type"=>$giftdetails['gifticon_type'],
                "giftcard_format"=>$giftdetails['giftcard_format'],
                "plain_code"=>$giftdetails['plain_code'],
                "pin"=>$giftdetails['pin'],
                "price"=>0,
                "normal_price"=>0,
                "purchase_date"=>date("Y-m-d H:i"),
                "expiry_date"=>$expdate,
                "qty"=>1
            );

            if($rewardDetail['size'] != '')
            {
            	$param['size'] = $rewardDetail['size'];
            }

            if(isset($giftdetails['giftcard_id']) && $giftdetails['giftcard_id'] != '')
            {
                $param['giftcard_id'] = $giftdetails['giftcard_id'];
            }

            $this->db->insert('tbl_purchases',$param);
            $p_id = $this->db->insert_id();
            
            if($giftdetails['gifticon_type'] == 0)
            {
                $qdata = "gifticon_id=".$rewardDetail['gifticon_id']."&giftdetails=".$giftdetails['name']."&user_id=".$user_id."&purchase_id=".$this->db->insert_id()."";

                $qrc = 'b_'.$giftdetails['business_id'].'_p_'.$p_id.'_'.strtotime(date("Y-m-d H:i:s"));//$this->RandomString();

                $scannervalue =  $this->qrGenerator([
                    'code' => $qrc,     // The unique code generate by your system. NOTE length <= 32
                    'mid' => 11748, // PARTNER_ID
                    'sign_type' => 'MD5',
                ]);

                $QRdata = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=".$scannervalue.base64_encode($qdata)."";

            }else{

                if($giftdetails['giftcard_format'] == 2)
                {
                    $qdata1 = "".$rewardDetail['gifticon_id']."_".$user_id."_".$p_id."";
                        
                }

                $this->db->update('tbl_giftcards',array('is_used'=>1),array('gifticon_id'=>$rewardDetail['gifticon_id'],'code'=>$giftdetails['plain_code']));
            }

            $datatobeupdate = array();

            if(isset($QRdata) && $QRdata != "")
            {
                $datatobeupdate['qr_code'] = $QRdata;
            }
            
            if(isset($qdata1) && $qdata1 != "")
            {
                $datatobeupdate['barcode_string'] = $giftdetails['plain_code'];
                $datatobeupdate['barcode_string_ids'] = $qdata1;
            }

            if(!empty($datatobeupdate))
            {
                $this->db->update('tbl_purchases',$datatobeupdate,array('id'=>$p_id));
            }

            // here i need to update reward point as zero

            $this->db->update('tbl_user',array('reward_points'=>0),array('id'=>$user_id));

            $message = ['code' => '1','message' =>'You have successfully claim a free gifticon!'];
            $this->response($message, REST_Controller::HTTP_OK);
        }

    }

    function saveAnalitics_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $type == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $postdata = $this->input->post();

        if($type == 'purchased_page' || $type == 'gift_friend_page')
        {
            $this->db->insert('tbl_analitics_logs',$postdata);
        }elseif($type == 'visitors_page')
        {
            if(isset($macaddress) && $macaddress != '')
            {
                $this->db->select("*");
                $this->db->from('tbl_analitics_logs');
                $this->db->where(" macaddress = '".$macaddress."' AND type = '".$type."' AND DATE(insertdate) = '".date('Y-m-d')."' ");
                $query = $this->db->get();
                if($query->num_rows() == 0)
                {
                    $this->db->insert('tbl_analitics_logs',$postdata);
                }   
            }
            else
            {
                $this->db->select("*");
                $this->db->from('tbl_analitics_logs');
                $this->db->where(" user_id = '".$user_id."' AND type = '".$type."' AND DATE(insertdate) = '".date('Y-m-d')."' ");
                $query = $this->db->get();
                if($query->num_rows() == 0)
                {
                    $this->db->insert('tbl_analitics_logs',$postdata);
                }
            }
        }
        else
        {
            $this->db->select("*");
            $this->db->from('tbl_analitics_logs');
            $this->db->where(" user_id = '".$user_id."' AND type = '".$type."' AND DATE(insertdate) = '".date('Y-m-d')."' ");
            $query = $this->db->get();
            if($query->num_rows() == 0)
            {
                $this->db->insert('tbl_analitics_logs',$postdata);
            }    
        }

        $message = ['code' => '1','message' =>'done'];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    function getMicrokey_post()
    {
        extract($this->input->post());
        if(!isset($user_id) || $user_id == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $API_KEY_Android = 'sRwAAAAMY29tLmdpZnRpY29uZWxExIAMCrXrJR8kJDrj5zLCmAPO2NnZxtr5z9fPseh2aYliZgD3xkAZ5wDLLjrtfrK8V3ymH0wh/eJcG8J/mYxa9m8qLlww44DsqQQfIs+J2oMwBqWPIS5RhO9nlzKt8nKzbviaAst5Rkvf2/SCb+korwlOEeJaguDJg7BEiEi461UkfOMZspttHTTnBLYF9deDAKbyIi8TO3o2qjm3czh0U5BIGSL/Ygky6hNp6Q==';

        $API_KEY_iOS = "sRwAAAEWY29tLnBvaW50emVyby5naWZ0aWNvboAO4qpjO9SWbke4azKQSAfIOjHN6aZ+La7jgo4DYNUQFNTP1o9+spbLm0qRNTBWZ8UbC57iou4uE3RwkBn+ieNH0MFNtiHdLRDhUjZGqAwE5m8LXzrhG6X9kBaqojNrk3KNkl1kWv6vMqgsjg3hojs1gYLgAZRWcPSrVz7oC0W5euw8vVxxcYUOA02JsgV1JSunXm2TuZyRpZ/0T0ll6z93JbJrDWyTUyh+/Ox9Rio=";

        $data = array('android_key'=>$API_KEY_Android,'ios_key'=>$API_KEY_iOS);
        $message = ['code' => '1','message' =>'success','data'=>$data];
        $this->response($message, REST_Controller::HTTP_OK);    
        
    }

    function checkQty_post()
    {

        extract($this->input->post());
        if(!isset($user_id) || $user_id == "" || $metadata == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $allpostdata = $this->input->post();
        $mdata = $allpostdata['metadata'];

        if($this->common->isJson($mdata))
        {
            $loop_data = json_decode($mdata,true);
            
            foreach ($loop_data as $key => $loopvalue) {
            	$gift = $this->db->get_where('tbl_gifticons',array('id'=>$loopvalue['gift_id']))->row_array();

                //if korea item
                if($gift['wincube_id'] != null)
                {
                    $client = new GuzzleHttp\Client();
                    $res = $client->request('POST', WINCUBE_API_BASE . 'check_goods.do', [
                        'query' => [
                            'mdcode' => 'gifticon_nz',
                            'goods_id' => $gift['wincube_id'],
                            'response_type' => 'JSON'
                        ]
                    ]);
                    $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
                    $check_wincube = json_decode($body, true);

                    //if wincube check_goods.do response have
                    if(count($check_wincube['goodslist']) > 0)
                    {
                        //if wincube check_goods.do response success
                        if($check_wincube['goodslist'][0]['goods_stus'] ==  '1')
                        {
                            $loop_data[$key]['qry_remaining'] = $this->User_model->qtyRemaing($loopvalue['gift_id']);
                            $loop_data[$key]['product_name'] = $gift['name'];
                        }else{
                            $loop_data[$key]['qry_remaining'] = "";
                            $loop_data[$key]['product_name'] = $gift['name'];
                        }
                    }else{
                        //if wincube check_goods.do response fail
                        $loop_data[$key]['qry_remaining'] = "";
                        $loop_data[$key]['product_name'] = $gift['name'];
                    }

                }else{
                    $loop_data[$key]['qry_remaining'] = $this->User_model->qtyRemaing($loopvalue['gift_id']);
                    $loop_data[$key]['product_name'] = $gift['name'];
                }
            }
			
			$message = ['code' => '1','message' => 'data found','data'=>$loop_data];
            $this->response($message, REST_Controller::HTTP_OK);

        }else{
            $message = ['code' => '0','message' => "Invalid json format"];
            $this->response($message, REST_Controller::HTTP_OK);
        }    
    }

    function appVersionCheck_post()
    {
        extract($this->input->post());
        if(!isset($device_type) || $device_type == "" || !isset($app_version) || $app_version == "")
        {
            $message = ['code' => '0','message' => 'Invalid Parameters'];
            $this->response($message, REST_Controller::HTTP_OK);  
        }

        $app_version_db = $this->db->get_where('tbl_app_version',array('os_type' => $device_type, 'version' => $app_version))->row_array();

        if($app_version)
        {
            if($app_version['is_force_update'])
            {
                $message = ['code' => '6','message' => $app_version_db];
                $this->response($message, REST_Controller::HTTP_OK);  
            }else{
                $message = ['code' => '7','message' => 'Simple update found.'];
                $this->response($message, REST_Controller::HTTP_OK);  
            }
        }
        $message = ['code' => '1','message' => 'Update not found.'];
        $this->response($message, REST_Controller::HTTP_OK); 

    }

}
?>
