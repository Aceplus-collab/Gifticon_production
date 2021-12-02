<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Home extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->library('form_validation');
    }
    public function login()
    {
        $data=$this->session->userdata();
        if(isset($data['id'])){
            redirect("Admin/dashboard");
        }else{
            $this->load->view('signin');    
        }
        
    }

    function notifications()
    {
        $data['page'] = "notification";
        
        $AllUsers = $this->db->get_where('tbl_user',array('is_active'=>1,'is_delete'=>0))->result_array();
        $data['AllUsers'] = $AllUsers;

        $AllBusiness = $this->db->get_where('tbl_businesses',array('is_active'=>1,'is_delete'=>0))->result_array();
        $data['AllBusiness'] = $AllBusiness;


        $this->load->view("admin/page/notification",$data);
    }

    function sendNotiUsers()
    {
        $postdata = $this->input->post();

        if(isset($postdata['message']) && $postdata['message'] != '')
        {
            $NotiMessage = $postdata['message'];    
        }

        $tag = 'admin_'.$postdata['type'];
        
        if($postdata['users'])
        {
            foreach($postdata['users'] as $key => $value)
            {
                // SELECT `id`, `title`, `user_id`, `user_type`, `sender_id`, `gifticon_id`, `purchase_id`, `image`, `message`, `tag`, `type`, `is_read`, `insertdate` FROM `tbl_notification` WHERE 1
                $this->db->insert('tbl_notification',array('title'=>$postdata['type'],'user_id'=>$value,'user_type'=>'U','message'=>$NotiMessage,'tag'=>$tag,'type'=>$postdata['type']));

                $UserDetails = $this->db->get_where('tbl_user',array('id'=>$value))->row_array();

                $msgpush = array("body"=>$NotiMessage,'title'=>strtoupper($postdata['type']),"tag"=>$tag,"type"=>$postdata['type']);

                $body = array(); 
                $bodyI['aps'] = array('sound'=>'default','mutable-content'=>1, 'alert' => array('title'=>strtoupper($postdata['type']),'body'=>$NotiMessage),"tag"=>$tag);

                if($UserDetails['device_type']=='A' && $UserDetails['device_id']!='')
                {
                    $registatoin_ids_D = $UserDetails['device_id'];
                    $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                }

                if($UserDetails['device_type']=='I' && $UserDetails['device_id']!='')
                {
                    $this->common->send_notification_ios_customer($bodyI,$UserDetails['device_id']);
                }
            }
            //die;

            $this->session->set_flashdata("succUser","Notification has been sent to selected users!");
            redirect("admin/home/notifications");
        }else{
            
            $this->session->set_flashdata("errorindi","Something went wrong");
            redirect("admin/home/notifications");
        }   
    }

    function sendNotiBusiness()
    {
        $postdata = $this->input->post();

        if(isset($postdata['message']) && $postdata['message'] != '')
        {
            $NotiMessage = $postdata['message'];    
        }else{
            $NotiMessage = 'hello';
        }

        $tag = 'admin_'.$postdata['type'];
        
        if($postdata['users'])
        {
            foreach($postdata['users'] as $key => $value)
            {
                // SELECT `id`, `title`, `user_id`, `user_type`, `sender_id`, `gifticon_id`, `purchase_id`, `image`, `message`, `tag`, `type`, `is_read`, `insertdate` FROM `tbl_notification` WHERE 1
                $this->db->insert('tbl_notification',array('title'=>$postdata['type'],'user_id'=>$value,'user_type'=>'B','message'=>$NotiMessage,'tag'=>$tag,'type'=>$postdata['type']));

                $UserDetails = $this->db->get_where('tbl_businesses',array('id'=>$value))->row_array();

                $msgpush = array("body"=>$NotiMessage,'title'=>strtoupper($postdata['type']),"tag"=>$tag,"type"=>$postdata['type']);

                $body = array(); 
                $bodyI['aps'] = array('sound'=>'default','mutable-content'=>1, 'alert' => array('title'=>strtoupper($postdata['type']),'body'=>$NotiMessage),"tag"=>$tag);

                if($UserDetails['device_type']=='A' && $UserDetails['device_id']!='')
                {
                    $registatoin_ids_D = $UserDetails['device_id'];
                    $this->common->send_fcm_notification($registatoin_ids_D,$msgpush);
                }

                if($UserDetails['device_type']=='I' && $UserDetails['device_id']!='')
                {
                    $this->common->send_notification_ios_business($bodyI,$UserDetails['device_id']);
                }
            }

            $this->session->set_flashdata("succUser","Notification has been sent to selected business!");
            redirect("admin/home/notifications");
        }else{
            
            $this->session->set_flashdata("errorindi","Something went wrong");
            redirect("admin/home/notifications");
        }   
    }



}
?>