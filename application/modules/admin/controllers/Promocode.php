<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Promocode extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('promocode_model');

        $this->load->model('my_model');
  
        $data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
        }
	}
    function add()
    {
        $data['admin']=$this->session->userdata();
        $data['page']="promocode";
        $this->load->view('promocode/add_promo',$data);
    }

	public function insert()
    {
        $this->form_validation->set_rules('code', 'code', 'required|is_unique[tbl_coupon_codes.code]',array("required"=>"Please provide code","is_unique"=>"code already exists!"));
        
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="promocode";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('promocode/add_promo',$data);
        }
        else
        {
            $Allpostdata = $this->input->post();

            $param= $Allpostdata;
            
            if($this->promocode_model->insert("tbl_coupon_codes",$param))
            {
                $this->session->set_flashdata("suc","Code added successfully!");
                redirect("admin/promocode/listing");
            } 
        }    
    }
    function listing()
    {
        $data['admin']=$this->session->userdata();
        $data['page']="promocode";
        $this->load->view('promocode/list',$data);
    }
    function ajax_list()
    {
        $user_list = $this->promocode_model->getAllUser();

        $data = array();
        if(!empty($user_list)) 
        {
        	
            foreach ($user_list as $key => $user) {
                $row = array();
                $row['id'] = $key+1;

                $row['code'] = $user['code'];
                $row['type'] = $user['type'];
                $row['sdate'] = $user['sdate'];
                $row['edate'] = $user['edate'];
                $row['discount'] = $user['discount'];
                $row['total_used'] = $user['total_used'];
                
                if($user['is_active']==='1'){
                    $row['is_active']="<button class='active label label-table label-success active' id='active'  value='".$user['id']."'>Active</button>";   
                }else{ 
                    $row['is_active']="<button class='active label label-table label-danger active' value='".$user['id']."'>Inactive</button>";   
                }

                $action = 
                '&nbsp;&nbsp;<a href='.base_url('admin/promocode/userEdit/').$user['id'].' class="btn btn-sm btn-primary" title="Edit">EDIT</i></a>';
                $action .= 
                '&nbsp;&nbsp;<button class="btn btn-info waves-effect waves-light btn-sm btn-danger delete"  value='.$user['id'].' title="Delete">DELETE</i></button>';

                $row['action'] = $action;

                $data[] = $row;
            }
        }

        $output = array(
            "total" => $this->promocode_model->count_filtered(),//count($this->promocode_model->getAllUser()),
            "rows" => $data
            );
        echo json_encode($output);
    }
    public function userEdit($id)
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['user']=$this->promocode_model->get_one_record($tbl_name="tbl_coupon_codes",$id);
        $data['page']="promocode";
        $this->load->view('promocode/update_promo',$data);
    }
    public function update()
    {
        $Allpostdata = $this->input->post();
        $promo_id = $Allpostdata['promo_id'];
        unset($Allpostdata['promo_id']);
     
        $param= $Allpostdata;

        if($this->promocode_model->update("tbl_coupon_codes",$param,$promo_id))
        {   
            $this->session->set_flashdata("suc","Update details successfully!");
            redirect("admin/promocode/listing");
        }else{
            $this->session->set_flashdata("msg","Update detail failed!");
            redirect("admin/promocode/listing");
        }
    }

    public function active_inactive(){
        $id=$_POST['id'];
        $tbl_name=$_POST['tbl_name'];
        $this->my_model->active_inactive($id,$tbl_name);
    }

    public function userDelete()
    {
        $id=$_POST['id'];
        $udetail = $this->db->get_where('tbl_coupon_codes',array('id'=>$id))->row_array();

        
        $this->db->delete('tbl_coupon_codes',array('id'=>$id));
    }
    
}
?>