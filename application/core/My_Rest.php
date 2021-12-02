<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class My_Rest extends REST_Controller {
	
	var $login_user_id;
	function __construct() {
		parent::__construct();

		/*$this->config->set_item('language',"english"); 
        $this->load->language('rest_controller', "english"); */
        
        if (!empty($this->response->lang))
        { 
            if(in_array($this->response->lang, $this->config->item('rest_language_supported'))) 
            { 
                $this->config->set_item('language',$this->response->lang); 
                $this->load->language('rest_controller', $this->response->lang);
            } 
            else
            { 
                $this->config->set_item('language',$this->config->item('language')); 
                $this->load->language('rest_controller', $this->config->item('language')); 
            } 
        } 
        else 
        { 
            $this->config->set_item('language',$this->config->item('language')); 
            $this->load->language('rest_controller', $this->config->item('language')); 
        }

		$this->load->model('common', '', TRUE);
		$this->login_user_id=$this->common->validate_header_token($this);
		$this->itemPerPage = 10;
		$this->load->helper('my_language_helper');


	}   
}

?>
