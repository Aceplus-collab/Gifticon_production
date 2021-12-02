<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Admin extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }
    public function index()
    {
        $this->load->view('signin');
    }
    public function login()
    {
        //echo "<pre>";print_r($_POST);die;
        if(isset($_POST['signin']) && !empty($_POST['signin']))
        {
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $data['email']=$this->input->post('email');
            if ($this->form_validation->run() == FALSE)
            {
                    //$this->load->view('signin',$data);
                    $formError = validation_errors();
                    $this->session->set_flashdata("msg",$formError);
                    redirect("admin/Home/login");
            }
            else
            {
                $data = array (
                    "email" => $this->input->post('email'),
                    "password" => hash('sha256', $this->input->post('password'))
                );
                $admin_detail = $this->db->get_where("tbl_admin",$data)->row_array();           
                if($admin_detail)
                {
                    if($admin_detail['is_active']=='1')
                    {
                        $this->session->set_userdata($admin_detail);//store admin data into session
                        $this->session->set_flashdata("suc","Login successfully!");
                        redirect("admin/Admin/dashboard");
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Your admin account is deactivated");
                        redirect("admin/Home/login");
                    }
                }
                else
                {
                    $error['invalid']="Invalid Email or Password";
                    $this->session->set_flashdata("msg","Invalid Email or Password");
                    redirect("admin/Home/login");
                }
            }
        }
    }
    public function dashboard()
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="dashboard";
        $this->load->view('dashboard',$data);
    } 
    public function AdminProfile()
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="admin_profile";
        $this->load->view('admin_profile',$data);
    }
    function ChangePassword()
    {
        $data['admin']=$this->session->userdata();
        $data['total_user']=$this->db->get_where("tbl_user",array("is_delete"=>"0"))->num_rows();
        $data['page']="change_password";
        $this->load->view('admin_change_password',$data);
    }
    function savePassword()
    {
        //echo "<pre>";print_r($_POST);die;
         $this->form_validation->set_rules('old_password', 'Old Password', 'required');
         $this->form_validation->set_rules('new_password', 'New Password', 'required');
         $this->form_validation->set_rules('c_password', 'Confirm Password', 'required');

         if ($this->form_validation->run() == FALSE)
            {
                    //$this->load->view('signin',$data);
                    $formError = validation_errors();
                    $this->session->set_flashdata("msg",$formError);
                    redirect("admin/ChangePassword");
            }
            else
            {
                $id=$this->input->post('id');
                $admin_detail = $this->db->get_where("tbl_admin",array("id"=>$id))->row_array();  
                       
                if($admin_detail)
                {
                    if($admin_detail['password']==hash('sha256', $this->input->post('old_password')))
                    {
                        $param=array(
                            "password"=>hash('sha256', $this->input->post('new_password'))
                        );
                        if($this->admin_model->update("tbl_admin",$param,$id))
                        {
                            $this->session->set_flashdata("suc","Change Password successfully!");
                            redirect("admin/Admin/dashboard"); 
                        }else{
                            $this->session->set_flashdata("msg","Change Password Process is failed");
                            redirect("admin/ChangePassword");
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Your Entered Old password is inccorect");
                        redirect("admin/ChangePassword");
                    }
                }
            }
    }
    public function Logout()
    {
        $this->session->unset_userdata('admin');
        $this->session->sess_destroy();
        $this->session->set_flashdata("suc","Logout successfully!");
        $this->index();
    }

}
?>