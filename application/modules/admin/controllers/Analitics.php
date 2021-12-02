<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * 
 */
class Analitics extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('analitics_model');

        $this->load->model('my_model');
  
        $data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
        }
	}
    
    function listing()
    {	
    	date_default_timezone_set('NZ');
    	
    	$postdata = $this->input->post();
    	if(isset($postdata['searchdate']) && $postdata['searchdate'] != '')
    	{
    		$searchdate = $postdata['searchdate'];
    	}else{
    		$searchdate = date('Y-m-d');
    	}

        $data['page']="analitics";
        $analitics_data = $this->analitics_model->getAnalitics($searchdate);
        $data['analitics_data'] = $analitics_data;
        $data['searchdate'] = $searchdate;


        $this->load->view('analitics/list',$data);
    }
}
?>