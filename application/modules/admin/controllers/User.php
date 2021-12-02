<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class User extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
  //       $this->load->library('session');
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
    function add()
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="user";
        $this->load->view('user/add_user',$data);
    }

	public function insert()
    {
        $this->form_validation->set_rules('username', 'User Name', 'required|is_unique[tbl_user.username]',array("required"=>"Please provide User Name","is_unique"=>"User Name already exists!"));
        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[tbl_user.email]',array("required"=>"Please provide email","is_unique"=>"Email already exists!"));
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="user";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('user/add_user',$data);
        }
        else
        {
            $Allpostdata = $this->input->post();

            unset($Allpostdata['c_password']);

            $Allpostdata['password']=hash('sha256', $Allpostdata['password']);
                 
            $param= $Allpostdata;
            if(isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['size'] > 0)
            {
                $profile_image = $this->common->media_upload_S3($field = 'profile_image',$path = "profile_image/");
                if ($profile_image) {

                    $param['profile_image'] = $profile_image;
                }
            }
            if($this->user_model->insert("tbl_user",$param))
            {
                $this->session->set_flashdata("suc","Add user successfully!");
                redirect("admin/user/listing");
            } 
        }    
    }
    function listing($isedit=0)
    {   
        if(isset($isedit) && $isedit == 0)
        {
            $this->session->unset_userdata('user_curr_page');    
        }

        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="user";
        $this->load->view('user/list',$data);
    }
    function ajax_list()
    {
        
        $user_list = $this->user_model->getAllUser();

        $data = array();
        if(!empty($user_list)) 
        {
        	
            foreach ($user_list as $key => $user) {
                $row = array();
                $row['id'] = $key+1;

                $row['profile_image'] = '<img class="img-responsive img-circle img-thumbnail thumb-md" src='.PROFILE_IMAGE.$user['profile_image'].' height="60" width="60"  alt="No image">';
                $row['name'] = $user['name'];
                $row['username'] = $user['username'];
                $row['email'] = $user['email'];
                $row['dob'] =  (($user['dob']) != '0000-00-00') ? date('d-m-Y',strtotime($user['dob'])) : '-';
                $row['country'] = $user['country'];
                $row['hear_about_us'] = $user['hear_about_us'];
                if($user['signup_type'] == 'G')
                {
                    $stype = 'Google';
                }elseif ($user['signup_type'] == 'F') {
                    $stype = 'Facebook';
                }elseif ($user['signup_type'] == 'A') {
                    $stype = 'Apple';
                }else{
                    $stype = 'Normal';
                }

                $row['signup_type'] = $stype;

                if($user['is_login']==='1'){
                    $row['is_login']="Yes";   
                }else{ 
                    $row['is_login']="No";   
                }

                if($user['is_active']==='1'){
                    $row['is_active']="<button class='active label label-table label-success active' id='active'  value='".$user['id']."'>Active</button>";   
                }else{ 
                    $row['is_active']="<button class='active label label-table label-danger active' value='".$user['id']."'>Inactive</button>";   
                }

                $row['purchased'] = '&nbsp;&nbsp;<a href='.base_url('admin/user/purchased/').$user['id'].' class="btn btn-sm btn-primary" title="History">Purchase</i></a>';
				
				$action = '<a href='.base_url('admin/user/userProfile/').$user['id'].' class="btn btn-sm btn-default" title="Edit">VIEW</i></a> ';
                $action.='&nbsp;&nbsp;<a href='.base_url('admin/user/userEdit/').$user['id'].' class="btn btn-sm btn-primary" title="Edit">EDIT</i></a>';
                
                $action .= 
                '&nbsp;&nbsp;<button class="btn btn-info waves-effect waves-light btn-sm btn-danger delete"  value='.$user['id'].' title="Edit">DELETE</i></button>';

                $row['action'] = $action;

                $data[] = $row;
            }
        }

        $output = array(
            "total" => $this->user_model->count_filtered(),//count($this->user_model->getAllUser()),
            "rows" => $data
            );
        echo json_encode($output);
    }
    public function userEdit($id)
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['user']=$this->user_model->get_one_record($tbl_name="tbl_user",$id);
        $data['page']="user";
        $this->load->view('user/update_user',$data);
    }
    public function update()
    {
        
        $this->form_validation->set_rules('username', 'User Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        
        $id=$this->input->post('user_id');
        $data['admin']=$this->session->userdata();
        $data['user']=$this->user_model->get_one_record($tbl_name="tbl_user",$id);
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="user";

        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('user/update_user',$data);   
        }
        else
        {
            
            $username=$this->user_model->checkUserNameExists($this->input->post('username'),$id);
            if(empty($username))
            {
                $email=$this->user_model->checkEmailExists($this->input->post('email'),$id);
                if(empty($email))
                {
                        $Allpostdata = $this->input->post();

                        unset($Allpostdata['user_id']);
                     
                        $param= $Allpostdata;

                        if($this->input->post('password') != '' )
                        {
                             $param['password'] = hash('sha256', $this->input->post('password'));
                        } 

                        if(isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['size'] > 0)
                        {
                            $profile_image = $this->common->media_upload_S3($field = 'profile_image',$path = "profile_image/");
                            if ($profile_image) {
                               
                                $param['profile_image'] = $profile_image;
                            }
                        }

                        if($this->user_model->update("tbl_user",$param,$id))
                        {   
                            $this->session->set_flashdata("suc","Update user successfully!");
                            //redirect("admin/user/userEdit/".$id);
                            redirect("admin/user/listing/1");
                        }else{
                            $this->session->set_flashdata("msg","Update user failed!");
                            redirect("admin/user/userEdit/".$id);
                        }
                   
                }else{    
                    $this->session->set_flashdata("msg","Email is already exists.");
                    redirect("admin/user/userEdit/".$id);
                }
            }
            else
            {    
                $this->session->set_flashdata("msg","Username is already exists.");
                redirect("admin/user/userEdit/".$id);
            }
        }
    }
    public function userProfile($id)
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['user']=$this->user_model->get_one_record($tbl_name="tbl_user",$id);
        $data['page']="user";
        $data['callback']=base_url()."admin/user/listing";
        $this->load->view('user/user_view',$data);
    }
   
    public function userDelete()
    {
        $id=$_POST['id'];
        $udetail = $this->db->get_where('tbl_user',array('id'=>$id))->row_array();

        $tbl_name=$_POST['tbl_name'];
        $data=array(
            "email"=>$udetail['email'].'_Deleted',
            "username"=>$udetail['username'].'_Deleted',
            "token"=>"",
            "device_id"=>"",
            "device_type"=>"",
            "is_delete"=>'1'
        );
        $this->user_model->update("tbl_user",$data,$this->input->post('id'));
        //$this->user_model->update($tbl_name,$id);
    }
    public function checkUserNameExists($username,$id=null)
    {
        $data=$this->user_model->checkUserNameExists($username,$id);
        if($data){
            return false;
        }else{
            return true;
        }  
    }
    public function active_inactive(){
        $id=$_POST['id'];
        $tbl_name=$_POST['tbl_name'];
        //echo $tbl_name;
        $this->user_model->active_inactive($id,$tbl_name);
    }
    function get_code()
    {
        if(isset($_POST['country_code']))
        {
            $data['selected_code']=$_POST['country_code'];
        }
        $data['country_code']=$this->db->get_where("tbl_country",array("is_active"=>"1"))->result_array();
        $this->load->view('user/country_code',$data);
    }

    function purchased($user_id)
    {
        $data['page']="user";
        $data['user_id'] = $user_id;
        $this->load->view('user/purchase_list',$data);
    }

    function purchase_ajax_list($user_id)
    {
        
        $category_list = $this->user_model->getPurchase($user_id);
        $data = array();
        if(!empty($category_list)) 
        {
            foreach ($category_list as $key => $category) {
                $row = array();
                $row['id'] = $key+1;

                $row['username'] = $category['username'];
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
                }
                else if($category['giftcard_format'] == 2)
                {
                    $row['giftcard_format'] = 'Barcode string : '.$category['plain_code'].'<br>'. 'Pin : '.$category['pin'];
                }

                $row['normal_price'] = $category['normal_price'];

                $row['price'] = $category['price'];

                $row['coupon_discount_amount'] = $category['coupon_discount_amount'];

                $row['is_redeem'] = ($category['is_redeem'] == '1') ? "Yes" : "No";

                $row['purchase_date'] = $category['purchase_date'];

                $row['redeem_date'] = $category['redeem_date'];

                $row['giftto_user_name'] = $category['giftto_user_name'];

                $row['giftfrom_user_name'] = $category['giftfrom_user_name'];

                $row['sent_sms_number'] = $category['sent_sms_number'];

                $row['edited_name'] = $category['edited_name'];

                if($category['image_name'] != '')
                {
                    $row['image_name'] = '<img width="200" height="200" src="'.'https://gnew.s3-eu-west-1.amazonaws.com/gift/'.$category['image_name'].'">';    
                }else{
                    $row['image_name'] = '';
                }

                if($category['txt_color'] != '')
                {
                    $row['txt_color'] = '<span style="color:'.$category['txt_color'].'">Text Color</span>';
                }else{
                    $row['txt_color'] = '';
                }                

                if($category['bg_color'] != '')
                {
                    $row['bg_color'] = '<span style="background-color:'.$category['bg_color'].'">Backgdound color</span>';
                }else{
                    $row['bg_color'] = '';
                }
                
                $row['user_notes'] = $category['user_notes'];

                $data[] = $row;
            }
        }
        $output = array(
            "total" => $this->user_model->count_filtered_purchase($user_id),
            "rows" => $data
            );
        echo json_encode($output);
    }
}
?>