<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Page extends CI_Controller {

    private $viewfolder = 'admin/page/';
	function __construct()
    {   
        parent::__construct();
        
        $this->load->model('pages_model');
        $data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
        }
    }

    /*CMS about us Page*/
    function about_us()
    {
      	$data['data'] = $this->db->get_where('tbl_content_pages',array('id'=>3))->row_array(); 
        $data['page'] = 'about';

		$data['page_sname'] = 'cms';        
        if(!empty($this->input->post('edit_about_us')) && $this->input->post('edit_about_us') == 'update')
        { 
            $this->form_validation->set_rules('contents','Contents','required|xss_clean');
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if($this->form_validation->run())     
            { 
    			$this->db->update('tbl_content_pages',array('contents'=>$this->input->post('contents')),array('id'=>3));
                
				$this->session->set_flashdata('succ_msg', 'About us contents updated successfully.');
                redirect('admin/page/about_us');
            }
            else
                $this->load->view($this->viewfolder.'about_us', $data);
        }
        else
        {
            $this->load->view($this->viewfolder.'about_us', $data);
        }
    }

    function terms()
    {
      	$data['data'] = $this->db->get_where('tbl_content_pages',array('id'=>1))->row_array(); 
        $data['page'] = 'terms';
        $data['page_sname'] = 'cms';
        if(!empty($this->input->post('edit_terms')) && $this->input->post('edit_terms') == 'update')
        { 
            $this->form_validation->set_rules('contents','Contents','required|xss_clean');
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if($this->form_validation->run())     
            { 
    			$this->db->update('tbl_content_pages',array('contents'=>$this->input->post('contents')),array('id'=>1));
                
				$this->session->set_flashdata('succ_msg', 'Terms & Condition contents updated successfully.');
                redirect('admin/page/terms');
            }
            else
                $this->load->view($this->viewfolder.'terms', $data);
        }
        else
        {
            $this->load->view($this->viewfolder.'terms', $data);
        }
    }

    function faq()
    {
      	$data['data'] = $this->db->get_where('tbl_content_pages',array('id'=>2))->row_array(); 
        $data['page'] = 'faq';
        $data['page_sname'] = 'cms';
        if(!empty($this->input->post('edit_faq')) && $this->input->post('edit_faq') == 'update')
        { 
            $this->form_validation->set_rules('contents','Contents','required|xss_clean');
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if($this->form_validation->run())     
            { 
    			$this->db->update('tbl_content_pages',array('contents'=>$this->input->post('contents')),array('id'=>2));
                
				$this->session->set_flashdata('succ_msg', 'Faq contents updated successfully.');
                redirect('admin/page/faq');
            }
            else
                $this->load->view($this->viewfolder.'faq', $data);
        }
        else
        {
            $this->load->view($this->viewfolder.'faq', $data);
        }
    }

    function privacy()
    {
      	$data['data'] = $this->db->get_where('tbl_content_pages',array('id'=>4))->row_array(); 
        $data['page'] = 'privacy';
        $data['page_sname'] = 'cms';
        if(!empty($this->input->post('edit_privacy')) && $this->input->post('edit_privacy') == 'update')
        { 
            $this->form_validation->set_rules('contents','Contents','required|xss_clean');
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            if($this->form_validation->run())     
            { 
    			$this->db->update('tbl_content_pages',array('contents'=>$this->input->post('contents')),array('id'=>4));
                
				$this->session->set_flashdata('succ_msg', 'Privacy Policy contents updated successfully.');
                redirect('admin/page/privacy');
            }
            else
                $this->load->view($this->viewfolder.'privacy', $data);
        }
        else
        {
            $this->load->view($this->viewfolder.'privacy', $data);
        }
    }

    function contact()
    {
        $data['page']="contact";
        $this->load->view($this->viewfolder.'contact',$data);
    }
    function cajax_list_new()
    {

        $user_list = $this->pages_model->getcontacts();
        $data = array();
        if(!empty($user_list)) 
        {
            foreach ($user_list as $key => $user) {
                $row = array();
                $row['id'] = $key+1;

               
                $row['username'] = $user['username'];
                $row['type'] =  ($user['type'] == 'B') ? "Business" : "Customer"; 
                $row['subject'] = $user['subject'];
                
                $action = 
                '&nbsp;&nbsp;<button class="btn btn-info waves-effect waves-light btn-sm btn-danger delete"  value='.$user['id'].' title="Delete">DELETE</i></button>';
                $row['action'] = $action;

                $data[] = $row;
            }
        }

        $output = array(
            "total" => $this->pages_model->count_filteredc(),
            "rows" => $data
            );
        echo json_encode($output);
    }

    function contactDelete()
    {
        $id=$_POST['id'];
       
        $tbl_name=$_POST['tbl_name'];
        
        $this->db->delete('tbl_contactus',array('id'=>$id));
    }

   
}
/* End of file Home.php */