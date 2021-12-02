<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reward extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('my_model');
        $this->load->model('reward_model');
        
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
            $this->session->unset_userdata('reward_curr_page');    
        }
    	$data['page']="reward";
        $this->load->view('reward/list',$data);
    }
    function ajax_list()
    {
        
    	$category_list = $this->reward_model->getAllUser();
        $data = array();
        if(!empty($category_list))
        {
        	foreach ($category_list as $key => $category) {
                $row = array();
                $row['id'] = $key+1;


                $row['country_name'] = $category['country_name'];
                $row['business_name'] = $category['bname'];
                $row['business_username'] = $category['busername'];

                $row['rsize'] = $category['rsize'];

                $row['image'] = '<img class="img-responsive img-circle img-thumbnail thumb-md" src='.GIFT_IMAGE.$category['image'].' height="60" width="60"  alt="No image">';

                if($category['gifticon_type'] == 0)
                {
                	$row['gifticon_type'] = 'gifticon';
                }else{
                	$row['gifticon_type'] = 'giftcard';
                }
                
                if($category['giftcard_format'] == 0)
                {
                    $gdata = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$category['id']))->result_array();

                    $html = 'Plain codes : <br>';
                    foreach ($gdata as $key => $value) {
                        $html.= 'code :'.$value['code'].'<br>';
                        $html.= 'note :'.$value['notes'].'<br>';
                    }

                	$row['giftcard_format'] = $html;

                }elseif($category['giftcard_format'] == 1)
                {
                	$row['giftcard_format'] = 'QR Code <br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="QR">';
                }elseif($category['giftcard_format'] == 2)
                {
                    $gdata = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$category['id']))->result_array();

                    $html = 'Barcodes : <br>';
                    foreach ($gdata as $key => $value) {
                        $html.= 'code :'.$value['code'].'<br>';
                        $html.= 'note :'.$value['notes'].'<br>';
                    }

                    $row['giftcard_format'] = $html;
                }

                $row['name'] = $category['name'];
                $row['normal_price'] = $category['normal_price'];

                $row['coupon_price'] = $category['coupon_price'];

                $row['sale_start_date'] = $category['sale_start_date'];

                $row['sale_end_date'] = $category['sale_end_date'];
				
				$row['description'] = $category['description'];

				$row['expiry_date'] = $category['expiry_date'];

                $row['valid_start_date'] = $category['valid_start_date'];

                $row['valid_end_date'] = $category['valid_end_date'];

                $row['terms'] = $category['terms'];

                $row['available_store'] = $category['available_store'];

                if($category['expiration_type'] == 0)
                {
                	$row['expiration_type'] = 'Specified Date';
                }elseif($category['expiration_type'] == 1)
                {
                	$row['expiration_type'] = '3 Months from Puchased';
                }elseif($category['expiration_type'] == 2)
                {
                	$row['expiration_type'] = '6 Months from Puchased';
                }
                elseif($category['expiration_type'] == 3)
                {
                	$row['expiration_type'] = '12 Months from Puchased';
                }
                
                if($category['ris_active']==='1'){
                    $row['is_active']="<button class='active label label-table label-success active' id='active'  value='".$category['rid']."'>Active</button>";   
                }else{ 
                    $row['is_active']="<button class='active label label-table label-danger active' value='".$category['rid']."'>Inactive</button>";   
                }

                $action ='&nbsp;&nbsp;<a href='.base_url('admin/reward/edit/').$category['rid'].' class="btn btn-sm btn-primary" title="Edit">EDIT</i></a>';
                $action .= '&nbsp;&nbsp;<button class="btn btn-info waves-effect waves-light btn-sm btn-danger delete" value='.$category['rid'].' title="Delete">DELETE</i></button>';
                $row['action'] = $action;

                $data[] = $row;
            }
        }
        $output = array(
            "total" => $this->reward_model->count_filtered(),
            "rows" => $data
            );
        echo json_encode($output);
    }

    function add($redirect_flg)
    {
        $data['admin']=$this->session->userdata();
        $data['page']="reward";
        
        if($redirect_flg == 'NZ')
        {
            $gifticonlist_nz = $this->reward_model->getNzlist();
            $data['data_list'] = $gifticonlist_nz;
            $data['country_id'] = 1;
            $data['country_name'] = 'New Zealand';
            $this->load->view('reward/add_reward',$data);    
        }else if($redirect_flg == 'AUS')
        {
            $gifticonlist_aus = $this->reward_model->getAuslist();
            $data['data_list'] = $gifticonlist_aus;
            $data['country_id'] = 2;
            $data['country_name'] = 'Australia';
            $this->load->view('reward/add_reward',$data);
        }
    }

    function edit($reward_id)
    {
        $data['admin']=$this->session->userdata();
        $data['page']="reward";
        $data['reward'] = $this->db->get_where('tbl_reward',array('id'=>$reward_id))->row_array();

        if($data['reward']['country_id'] == 1)
        {
             $data['data_list'] = $this->reward_model->getNzlist();
        }else{
            $data['data_list'] = $this->reward_model->getAuslist();
        }
        
        $this->load->view('reward/edit_reward',$data);
    }

    function insert()
    {
        $this->form_validation->set_rules('country_id', 'country_id', 'required',array("required"=>"Please provide country_id"));

        $data['page']="reward";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('reward/add_reward',$data);
        }
        else
        {
            $AllPostdata = $this->input->post();

            extract($this->input->post());

            $gifts = $AllPostdata['gifticons'];

            if($gifts)
            {
                foreach ($gifts as $key => $value) {
                    $gdt = $this->db->get_where('tbl_gifticons',array('id'=>$value))->row_array();

                    $checkAlready = $this->db->get_where('tbl_reward',array('country_id'=>$country_id,'business_id'=>$gdt['business_id'],'gifticon_id'=>$value,'is_delete'=>0))->row_array();
                    if(empty($checkAlready))
                    {
                        $this->db->insert('tbl_reward',array('country_id'=>$country_id,'business_id'=>$gdt['business_id'],'gifticon_id'=>$value));
                    }
                }
            }
            
            $this->session->set_flashdata("suc","Reward added successfully!");
            redirect("admin/reward/listing");
        }
    }

    function update()
    {
        $AllPostdata = $this->input->post();

        extract($this->input->post());

        $rid = $AllPostdata['reward_id'];

        $this->db->update('tbl_reward',array('size'=>$AllPostdata['size']),array('id'=>$rid));
        
        $this->session->set_flashdata("suc","size successfully updated!");
        redirect("admin/reward/listing");
    }

    
    public function Delete()
    {
        $id=$_POST['id'];

        $this->db->update('tbl_reward',array('is_delete'=>'1'),array('id'=>$id));
	}

    public function active_inactive(){
        $id=$_POST['id'];
        $tbl_name=$_POST['tbl_name'];
        $this->my_model->active_inactive($id,$tbl_name);
    }
}
?>