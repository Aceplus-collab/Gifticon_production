<?php
class Pages_model extends CI_Model
{
    public $column_search = array('q.question', 'q.answer');
    public $order = array('q.id' => 'desc');

    function __construct()
    {
        error_reporting(E_ERROR);
        parent::__construct();
        $this->load->database();
        $this->utc_time = time();
        $this->output->enable_profiler(false);
    }

     // Pages Model Start
    function _get_datatables_query($type)
    {
        $this->db->select('q.*');
        $this->db->from('tbl_faq q');
        $this->db->where('type',$type);
        $i = 0;

        if (isset($_REQUEST['status']) && !empty($_REQUEST['status'])) {
            if ($_REQUEST['status'] == 'Active') {
                $this->db->where('q.status', 'Active');
            } elseif ($_REQUEST['status'] == 'Inactive') {
                $this->db->where('q.status', 'Inactive');
            }
        }
        foreach ($this->column_search as $item) { // loop column
            if (isset($_REQUEST['search'])) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); 
                    $this->db->like($item, $this->db->escape_like_str($_REQUEST['search']));
                } else {
                    $this->db->or_like($item, $this->db->escape_like_str($_REQUEST['search']));
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end();
                } //close bracket
            }
            ++$i;
        }

        if (isset($_REQUEST['order'])) { // here order processing
            $this->db->order_by($_REQUEST['sort'], $_REQUEST['order']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    /* get datatables for faq listing */
    function get_datatables($type)
    {
        $this->_get_datatables_query($type);

        if ($_REQUEST['limit'] != -1) {
            $this->db->limit($_REQUEST['limit'], $_REQUEST['offset']);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    /* get count of filtered records for faq listing */
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();

        return $query->num_rows();
    }

    /* add new quetion in faq listing */
    function question_data($question_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_faq');
        $this->db->where('id', $question_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->row_array();
        } else {
            return array();
        }
    }

     function count_filteredc()
    {
        $this->_get_datatables_queryc();
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_queryc()
    {
        $this->db->select('u.*');
        $this->db->from('tbl_contactus as u');
        $i = 0;
        $order_by = array('u.id' => 'desc'); // Default order by
        $search_fields = array('u.user_id'); //search fields
        foreach ($search_fields as $item) // loop column 
        {
            if(isset($_REQUEST['search'])) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. 
                    $this->db->like($item, $_REQUEST['search']);
                }
                else
                {
                    $this->db->or_like($item, $_REQUEST['search']);
                }
 
                if(count($search_fields) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_REQUEST['order'])) // here order processing
        {
            $this->db->order_by($_REQUEST['sort'], $_REQUEST['order']);
        } 
        else if(isset($order_by))
        {
            $order = $order_by;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function getcontacts()
    {
        $this->_get_datatables_queryc();
        if($_REQUEST['limit'] != -1)
        $this->db->limit($_REQUEST['limit'], $_REQUEST['offset']);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $data = array();
            foreach ($query->result_array() as $key => $value) {
                if($value['type'] == 'B')
                {
                    $userdata = $this->db->get_where('tbl_businesses',array('id'=>$value['user_id']))->row_array();
                }else{
                    $userdata = $this->db->get_where('tbl_user',array('id'=>$value['user_id']))->row_array();    
                }
                

                $value['username'] = $userdata['username'];
                $data[] = $value;
            }

            return $data;
        }
        else
        {
            return false;
        }
    }   
}
