<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Event extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
            
        }
    }
    function add()
    {

    }
    function insert()
    {
        
    }
}
?>