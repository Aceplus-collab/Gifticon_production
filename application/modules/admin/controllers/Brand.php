<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Brand extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('my_model');
        $this->load->model('brand_model');
        //$this->load->library('session');
        //$this->load->library('form_validation');

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
        $data['occasion'] = $this->db->get_where('tbl_occasions',array('is_active'=>1))->result_array();
        $data['tags'] = $this->db->get_where('tbl_tags')->result_array();
        $data['country'] = $this->db->get_where('tbl_gift_country')->result_array();
        $data['page']="brand";
        $this->load->view('brand/add_brand',$data);
    }
    function insert()
    {
        if($_FILES['image']['name']==''){
            $this->form_validation->set_rules('image', 'Image', 'required');
        }
        
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[tbl_businesses.username]',array("required"=>"Please provide brand Name","is_unique"=>"Brand username already exists!"));

        $data['page']="brand";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('brand/add_brand',$data);
        }
        else
        {
            $AllPostdata= $this->input->post();

            $country_data = $AllPostdata['country_ids'];

            unset($AllPostdata['country_ids']);
            
            $tags = $AllPostdata['tags'];

            unset($AllPostdata['tags']);

            $occasions = $AllPostdata['occasion'];

            unset($AllPostdata['occasion']);

            $AllPostdata['password'] = hash('sha256', $this->input->post('password'));

            unset($AllPostdata['c_password']);

            if(isset($_FILES['image']['name']) && $_FILES['image']['size'] > 0)
            {
                $image = $this->common->media_upload_S3($field = 'image',$path = "brand/");
                if ($image) {
                    $AllPostdata['image'] = $image;
                }
            } 
           
            $this->db->insert('tbl_businesses',$AllPostdata);
            $brand_id = $this->db->insert_id();

             $this->db->update('tbl_businesses',array('sequence'=>$brand_id),array('id'=>$brand_id));

            /*$AllUsers = $this->db->get_where('tbl_user',array('is_active'=>1,'is_delete'=>0))->result_array();

            $NotiMessage = 'New CLient Updated - '.$request_data['name'];

            foreach($AllUsers as $key => $value)
            {
                $this->db->insert('tbl_notification',array('title'=>$NotiMessage,'user_id'=>$value,'user_type'=>'U','message'=>$NotiMessage,'tag'=>'new','type'=>'new'));

                $UserDetails = $this->db->get_where('tbl_user',array('id'=>$value))->row_array();

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

           

            if(!empty($tags))
            {
                foreach ($tags as $key => $value) 
                {
                    $this->db->insert('tbl_business_tags',array('business_id'=>$brand_id,'tag_id'=>$value));
                }
            }

            if(!empty($occasions))
            {
                foreach ($occasions as $key => $value) 
                {
                    $this->db->insert('tbl_business_occasion',array('business_id'=>$brand_id,'occasion_id'=>$value));
                }    
            }
            
            //$country = $this->db->get_where('tbl_gift_country',array('is_active'=>1))->result_array();
            if(!empty($country_data))
            {
                foreach ($country_data as $key => $value) {
                    $this->db->insert('tbl_business_country',array('business_id'=>$brand_id,'gift_country_id'=>$value));
                }
            }

            $this->session->set_flashdata("suc","Brand added successfully!");
            redirect("admin/brand/listing"); 
        }
    }
    function listing($isedit=0)
    {
        if(isset($isedit) && $isedit == 0)
        {
            $this->session->unset_userdata('brand_curr_page');    
        }
    	$data['page']="brand";
        $this->load->view('brand/list',$data);
    }
    function ajax_list()
    {
    	
    	$category_list = $this->brand_model->getAllUser();
        $data = array();
        if(!empty($category_list)) 
        {
            foreach ($category_list as $key => $category) {
                $row = array();
                $row['id'] = $key+1;

                $row['merchant_id'] = $category['merchant_id'];

                $row['image'] = '<img class="img-responsive img-circle img-thumbnail thumb-md" src='.BRAND_IMAGE.$category['image'].' height="60" width="60"  alt="No image">';
                $row['name'] = $category['name'];

                $row['username'] = $category['username'];

                $row['email'] = $category['email'];

                $row['phone'] = $category['phone'];

                $row['description'] = $category['description'];

                $row['website'] = $category['website'];

                $row['commision_rate'] = $category['commision_rate'];

                $row['sequence'] = $category['sequence'];
                $row['update_date'] = $category['update_date'];

                $row['purchased'] = '&nbsp;&nbsp;<a href='.base_url('admin/brand/purchased/').$category['id'].' class="btn btn-sm btn-primary" title="History">Purchase</i></a>';

                if($category['is_active']==='1'){
                    $row['is_active']="<button class='active label label-table label-success active' id='active'  value='".$category['id']."'>Active</button>";   
                }else{ 
                    $row['is_active']="<button class='active label label-table label-danger active' value='".$category['id']."'>Inactive</button>";   
                }

                $action ='&nbsp;&nbsp;<a href='.base_url('admin/brand/brandEdit/').$category['id'].' class="btn btn-sm btn-primary" title="Edit">EDIT</i></a>';
                
                $action .= '&nbsp;&nbsp;<button class="btn btn-info waves-effect waves-light btn-sm btn-danger delete"  value='.$category['id'].' title="Edit">DELETE</i></button>';
                $row['action'] = $action;

                $data[] = $row;
            }
        }
        $output = array(
            "total" => $this->brand_model->count_filtered(),
            "rows" => $data
            );
        echo json_encode($output);
    }
   

    function brandEdit($id)
    {
        $data['brand']=$this->my_model->get_one_record($tbl_name="tbl_businesses",$id);
        $data['page']="brand";

        $data['tags'] = $this->db->get_where('tbl_tags')->result_array();

        $data['country'] = $this->db->get_where('tbl_gift_country')->result_array();

        $yy = $this->db->get_where('tbl_business_tags',array('business_id'=>$id))->result_array();
        $fdata = array();
        if($yy)
        {
            foreach ($yy as $key => $value) {
            $fdata[] = $value['tag_id'];
            }   
        }
        
        $data['brand_tags'] = $fdata; 

        $data['occasion'] = $this->db->get_where('tbl_occasions',array('is_active'=>1))->result_array();

        $oo = $this->db->get_where('tbl_business_occasion',array('business_id'=>$id))->result_array();
        $odata = array();
        if($oo)
        {
            foreach ($oo as $key => $value) {
            $odata[] = $value['occasion_id'];
            }   
        }
        
        $data['brand_occasions'] = $odata;

        $bco = $this->db->get_where('tbl_business_country',array('business_id'=>$id))->result_array();
        $bcodata = array();
        if($bco)
        {
            foreach ($bco as $key => $value) {
                $bcodata[] = $value['gift_country_id'];
            }   
        }
        
        $data['brand_country'] = $bcodata;



        $this->load->view('brand/brand_update',$data);
    }
    function update()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');

        $data['page']="brand";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('brand/brand_update',$data);
        }
        else
        {
            $category_exists=$this->checkCategoryExists($this->input->post('username'),$this->input->post('id'));
            if($category_exists)
            {
                $AllPostdata = $this->input->post();

                $brand_id = $AllPostdata['id'];
                unset($AllPostdata['id']);
                $tags = array();
                $occasions = array();
                $country_data = array();
                if(isset($AllPostdata['tags']) && !empty($AllPostdata['tags']))
                {
                    $tags = $AllPostdata['tags'];
                    unset($AllPostdata['tags']);    
                }

                if(isset($AllPostdata['occasion']) && !empty($AllPostdata['occasion']))
                {
                    $occasions = $AllPostdata['occasion'];
                    unset($AllPostdata['occasion']);    
                }

                if(isset($AllPostdata['country_ids']) && !empty($AllPostdata['country_ids']))
                {
                    $country_data = $AllPostdata['country_ids'];
                    unset($AllPostdata['country_ids']);    
                }
                
                if($AllPostdata['password'] && $AllPostdata['password'] != "")
                {
                    $AllPostdata['password'] = hash('sha256', $this->input->post('password'));    
                }else{
                    unset($AllPostdata['password']);
                }
                
                 if(isset($_FILES['image']['name']) && $_FILES['image']['size'] > 0)
                {
                    $image = $this->common->media_upload_S3($field = 'image',$path = "brand/");
                    if ($image) {

                        $AllPostdata['image'] = $image;
                    }
                }
                if($this->my_model->update("tbl_businesses",$AllPostdata,$brand_id))
                {
                    
                    if(!empty($tags))
                    {
                        $this->db->delete('tbl_business_tags',array('business_id'=>$brand_id));

                        foreach ($tags as $key => $value) 
                        {
                            $this->db->insert('tbl_business_tags',array('business_id'=>$brand_id,'tag_id'=>$value));
                        }
                    }    

                    if(!empty($occasions))
                    {
                        $this->db->delete('tbl_business_occasion',array('business_id'=>$brand_id));

                        foreach ($occasions as $key => $value) 
                        {
                            $this->db->insert('tbl_business_occasion',array('business_id'=>$brand_id,'occasion_id'=>$value));
                        }    
                    }

                    if(!empty($country_data))
                    {
                        $this->db->delete('tbl_business_country',array('business_id'=>$brand_id));

                         foreach ($country_data as $key => $value) {
                            $this->db->insert('tbl_business_country',array('business_id'=>$brand_id,'gift_country_id'=>$value));
                         }
                    }

                    $this->session->set_flashdata("suc","Update Brand successfully!");
                    redirect("admin/brand/listing/1");
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Update Brand Failed!");
                    redirect("admin/brand/brandEdit/".$brand_id);
                } 
            }
            else
            {
                $data['category_exists']="Brand username is already exists.";
                $this->load->view('brand/brand_update',$data);
            }
        }
    }
    public function checkCategoryExists($name,$id=null)
    {
        $data=$this->brand_model->checkCategoryExists($name,$id);
        if($data){
            return false;
        }else{
            return true;
        }  
    }
    public function Delete()
    {
        $id=$_POST['id'];

        $getdata = $this->db->get_where('tbl_businesses',array('id'=>$id))->row_array();

        $email = $getdata['email'].'_deleted';

        $username = $getdata['username'].'_deleted';

        $this->db->update('tbl_businesses',array('email'=>$email,'username'=>$username,'token'=>"",'device_id'=>"",'device_type'=>"",'is_delete'=>'1'),array('id'=>$id));
    }
    public function active_inactive(){
        $id=$_POST['id'];
        $tbl_name=$_POST['tbl_name'];
        $this->my_model->active_inactive($id,$tbl_name);
    }

    function purchased($brand_id)
    {
        $data['page']="brand";
        $data['brand_id'] = $brand_id;
        $this->load->view('brand/purchase_list',$data);
    }

    function purchase_ajax_list($brand_id)
    {
        
        $category_list = $this->brand_model->getPurchase($brand_id);
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

                $row['giftto_user_name'] = $category['giftto_user_name'];

                $row['giftfrom_user_name'] = $category['giftfrom_user_name'];

                $data[] = $row;
            }
        }
        $output = array(
            "total" => $this->brand_model->count_filtered_purchase($brand_id),
            "rows" => $data
            );
        echo json_encode($output);
    }
}
?>