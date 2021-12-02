<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Forgotpassword extends CI_Controller
{
	
	function __construct(){
		parent::__construct();		
		$this->load->model('api/User_model');
	}

	function newpassword($forgot_pass = "",$is_success=""){
		$data = array();	
		if($forgot_pass != ""){
			$condition = array("forgot_pass"=>$forgot_pass);
			$user_detail = array();
			
			$user_detail = $this->db->get_where("tbl_user",$condition)->row_array();	
			
			//print($is_success);die;
			if($is_success != "")
			{	
				$user_detail = array();
				$condition = array("id"=>$is_success);

				$user_detail = $this->db->get_where("tbl_user",$condition)->row_array();
				
				if($user_detail)
				{
					$this->session->set_flashdata('success_message','Password successfully change! Now you can login with newly set password.'); 
					// $this->lang->line("Password_change_success")
					$user_detail['is_success'] = $is_success;
				}				
			}
			else
			{
				//echo "string";
				$this->session->set_flashdata('success_message', '');
			}
			$data['user_detail'] = $user_detail;
			$this->load->view("set-new-password-form",$data);
		}
		else{
			$data['user_detail'] = array();
			$this->load->view("set-new-password-form",$data);
		}
	}

	function saveNewPassword(){		
		$this->lang->load("rest_controller");
		$this->form_validation->set_rules('confirmpassword', 'New Password', 'required',array("required"=>"New password is missing"));
		$this->form_validation->set_rules('user_id', 'User Id', 'required',array("required"=>"User Id is missing"));
		$this->form_validation->set_rules('forgot_pass', 'forgot pass', 'required',array("required"=>"Code is missing"));
		$form_data = $this->input->post();
		if($this->form_validation->run()) {
			$is_success = "";
			$data = array("password"=>md5($this->input->post('newpassword')),"forgot_pass"=>"");
			$condition=array("id"=>$form_data['user_id']);
			
			$is_success = $this->db->update("tbl_user",$data,$condition);	
			
			if($is_success){
				$this->session->set_flashdata('success_message', 'Password Change successfully!'); 				
				redirect('forgotpassword/newpassword/'.$form_data['user_id']);
			}
			else{
				$this->session->set_flashdata('error_message', 'Something went wrong! failed to set you new password'); 
				redirect('forgotpassword/newpassword/'.$form_data['forgot_pass']);	
			}	
		} 	
		else{
			$fields_validation = validation_errors();          
			$this->session->set_flashdata('error_message', $fields_validation);			
			redirect('forgotpassword/newpassword/'.$form_data['forgot_pass']);
		}
	}
}

?>