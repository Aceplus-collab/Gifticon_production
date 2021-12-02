<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Enc_demo extends CI_Controller {

    function __construct()
    {   
        // Construct the parent class
        parent::__construct();
    }
/*=============================================================================================================================
            Update Check API
=============================================================================================================================*/
    
    public function index()
    {
        $this->load->view('encryption');
    }

    public function save()
    {
        //update
        $AllPostData = $this->input->post();

        /* echo "<pre>";
        print_r($AllPostData);die(); */

        $this->load->library('form_validation');
       
        
        $this->form_validation->set_rules('data', 'data', 'required|trim');
        $this->form_validation->set_rules('type', 'type', 'required|trim');
        

        if ($this->form_validation->run() == FALSE) 
        {
            $this->load->view('encryption');
        } 
        else 
        {

            if ($AllPostData['type'] == 'encrypt') {

                $data['decrypt_value'] = $AllPostData['data'];
                $data['encrypt_value'] = trim($this->common->AES_encrypt($AllPostData['data']));

                $this->load->view('encryption',$data);
            }
            else{

                $data['decrypt_value'] = trim($this->common->AES_decrypt($AllPostData['data']));
                $data['encrypt_value'] = $AllPostData['data'];
                $this->load->view('encryption',$data);
            }

            //print_r($data);die;

        }
    }

}
?>