<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


/**
 * 
 */
class MY_Model extends CI_Model
{
	public function __construct(){
        parent::__construct();

    }
    //insert into database 
    public function insert($tbl_name,$data)
    {
    	return $this->db->insert($tbl_name,$data);
    }
    public function list($tbl_name){
    	$query=$this->db->get($tbl_name);
    	return $query->result();
    }
    public function list_where($tbl_name,$data)
    {
        $query=$this->db->get_where($tbl_name,$data);
        return $query->result();
    }
    public function checkEmail($tbl_name,$email)
    {
        $query=$this->db->get_where($tbl_name,array("email"=>$email));
        return $query->row_array();
    }
    // public function updateEmail($tbl_name,$email,$id)
    // {
    //     $query=$this->db->get_where($tbl_name,array("email"=>$email,"id"=>$id));
    //     return $query->row_array();
    // }
    public function updatePassword($tbl_name,$email,$pass)
    {
        $this->db->where('email',$email);
        if($this->db->update($tbl_name,array('password'=>$pass)))
        {
            return true;
        }else{
            return false;
        }
    }
    public function get_one_record($tbl_name,$id)
    {
    	$query=$this->db->get_where($tbl_name,array("id"=>$id));
    	return $query->row_array();
    }
    public function get_one_row($tbl_name,$data)
    {
        $query=$this->db->get_where($tbl_name,$data);
        return $query->row_array();
    }
    public function get_food_type(){
        $query=$this->db->get("tbl_food_type");
        return $query->result_array();
    }
    public function update($tbl_name,$data,$id)
    {
        $this->db->where('id', $id);
    	if($this->db->update($tbl_name,$data))
        {
            return true;
        }else{
            return false;
        }
    }
    public function delete($tbl_name,$id)
    {
        if($query=$this->db->delete($tbl_name,array("id"=>$id)))
        {   
            return true;
        }
        else{
            return false;
        }
    }
    public function checkEmailExists($email,$id=null){
        if($id==null)
        {
            return $this->db->get_where("tbl_user",array("email"=>$email))->result();
        }else{
            return $this->db->get_where("tbl_user",array("email"=>$email,"id !="=>$id))->result();
        }
    }
    public function checkPhoneExists($mobile,$id=null){
        if($id==null)
        {
            return $this->db->get_where("tbl_user",array("phone"=>$mobile))->result();
        }else{
            return $this->db->get_where("tbl_user",array("phone"=>$mobile,"id !="=>$id))->result();
        }
    }
    public function active_inactive($id,$tbl_name)
    {
        $query=$this->db->get_where($tbl_name,array('id'=>$id));
        $data=$query->row_array();
        
        if($data['is_active']=="1"){
            $this->db->where('id',$id);
            $query=$this->db->update($tbl_name,array('is_active'=>"0"));
        }else{
            $this->db->where('id',$id);
            $query=$this->db->update($tbl_name,array('is_active'=>"1"));
        }
        //echo "<pre>";print_r($data);die;
    }
}
?>
