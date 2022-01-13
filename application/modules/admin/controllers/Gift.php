<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Gift extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        $this->load->model('my_model');
        $this->load->model('gift_model');
        
        $data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
        }

        if(!empty($_REQUEST['limit']))
        {
            $page = ($_REQUEST['offset'] != 0) ? ($_REQUEST['offset'] / $_REQUEST['limit'])+1 : 1;
            $this->session->set_userdata('page', $page);
        }
        if(!empty($this->uri->segment(3)) && !in_array($this->uri->segment(3), array("listing")))
        {
            $this->session->set_userdata($this->uri->segment(2).'_curr_page', $this->session->userdata('page'));
        }
    }
   
    function listing($isedit=0)
    {
        $this->load->library('migration');
        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        }

        if(isset($isedit) && $isedit == 0)
        {
            $this->session->unset_userdata('gift_curr_page');    
        }
    	$data['page']="gift";
        $this->load->view('gift/list',$data);
    }
    function ajax_list()
    {
        
    	$category_list = $this->gift_model->getAllUser();
        $data = array();
        if(!empty($category_list)) 
        {
        	foreach ($category_list as $key => $category) {
                $row = array();
                $row['id'] = $key+1;

                $row['business_name'] = $category['bname'];
                $row['business_username'] = $category['busername'];

                $image_url = isset($category['wincube_image']) ? $category['wincube_image'] : GIFT_IMAGE . $category['image'];
                $row['image'] = '<img class="img-responsive img-circle img-thumbnail thumb-md" src='.$image_url.' height="60" width="60"  alt="No image">';

                if($category['gifticon_type'] == 0)
                {
                	$row['gifticon_type'] = 'gifticon';
                }else{
                	$row['gifticon_type'] = 'giftcard';
                }
                
                if($category['giftcard_format'] == 0)
                {
                    $gdata = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$category['id']))->result_array();

                    $html = 'Plain codes : <br>';
                    foreach ($gdata as $key => $value) {
                        $html.= 'code :'.$value['code'].'<br>';
                        $html.= 'note :'.$value['notes'].'<br>';
                    }

                	$row['giftcard_format'] = $html;

                }elseif($category['giftcard_format'] == 1)
                {
                	$row['giftcard_format'] = 'QR Code <br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="QR">';
                }elseif($category['giftcard_format'] == 2)
                {
                    $gdata = $this->db->get_where('tbl_giftcards',array('gifticon_id'=>$category['id']))->result_array();

                    $html = 'Barcodes : <br>';
                    foreach ($gdata as $key => $value) {
                        $html.= 'code :'.$value['code'].'<br>';
                        $html.= 'note :'.$value['notes'].'<br>';
                    }

                    $row['giftcard_format'] = $html;

                	//$row['giftcard_format'] = 'Barcode pin : '.$category['pin'].'<br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="Barcode">';
                }

                $row['name'] = $category['name'];
                $row['normal_price'] = $category['normal_price'];

                $row['coupon_price'] = $category['coupon_price'];

                $row['sale_start_date'] = $category['sale_start_date'];

                $row['sale_end_date'] = $category['sale_end_date'];
				
				$row['description'] = $category['description'];

				$row['expiry_date'] = $category['expiry_date'];

                $row['valid_start_date'] = $category['valid_start_date'];

                $row['valid_end_date'] = $category['valid_end_date'];

                $row['terms'] = $category['terms'];

                $row['available_store'] = $category['available_store'];

                $row['size'] = $category['size'];

                $row['sequence'] = $category['sequence'];

                $row['purchased'] = '&nbsp;&nbsp;<a href='.base_url('admin/gift/purchased/').$category['id'].' class="btn btn-sm btn-primary" title="History">Purchase</i></a>';

                if($category['expiration_type'] == 0)
                {
                	$row['expiration_type'] = 'Specified Date';
                }elseif($category['expiration_type'] == 1)
                {
                	$row['expiration_type'] = '3 Months from Puchased';
                }elseif($category['expiration_type'] == 2)
                {
                	$row['expiration_type'] = '6 Months from Puchased';
                }
                elseif($category['expiration_type'] == 3)
                {
                	$row['expiration_type'] = '12 Months from Puchased';
                }

                // $row['check_qty'] = "<button class='check-qty-btn' data-gift-id='".$category['wincube_id']."'>Check Qty</button>";
                
                if($category['is_active']==='1'){
                    $row['is_active']="<button class='active label label-table label-success active' id='active'  value='".$category['id']."'>Active</button>";   
                }else{ 
                    $row['is_active']="<button class='active label label-table label-danger active' value='".$category['id']."'>Inactive</button>";   
                }

                $action ='&nbsp;&nbsp;<a href='.base_url('admin/gift/giftEdit/').$category['id'].' class="btn btn-sm btn-primary" title="Edit">EDIT</i></a>';
                
                $action .= '&nbsp;&nbsp;<button class="btn btn-info waves-effect waves-light btn-sm btn-danger delete" value='.$category['id'].' title="Edit">DELETE</i></button>';
                $row['action'] = $action;

                $data[] = $row;
            }
        }
        $output = array(
            "total" => $this->gift_model->count_filtered(),
            "rows" => $data
            );
        echo json_encode($output);
    }

    function add()
    {
        $data['admin']=$this->session->userdata();
        $data['page']="gift";
        $data['business'] = $this->db->get_where('tbl_businesses',array('is_delete'=>0))->result_array();
        $data['tags'] = $this->db->get_where('tbl_tags')->result_array();
        $data['country'] = $this->db->get_where('tbl_gift_country')->result_array();
        $data['sizes'] = array('Small','Regular','Medium','Large');
        $this->load->view('gift/add_gift',$data);
    }

    function add_wincube()
    {
    	$data['page'] = "gift";
        $this->load->view('gift/add_wincube', $data);
    }

    /**
     * Fetches goods data from WinCube API and serves them via Ajax
     */
    function ajax_wincube_goods()
    {
        $this->output->set_content_type('json');

        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', WINCUBE_API_BASE . 'salelist.do', [
            'query' => [
                'mdcode' => 'gifticon_nz',
                'response_type' => 'JSON'
            ]
        ]);
        $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
        $goods = json_decode($body, true)['goods_list'];
        if (empty($goods)) {
            echo json_encode(['message' => 'Something was wrong querying WinCube', 'wincube_response' => $body]);
            die;
        }
        $goods_with_img = array_map(function ($item) {
            return array_merge($item, [
                'goods_img_html' => "<img class='img-responsive img-circle img-thumbnail thumb-md' src='{$item['goods_img']}' height='60' width='60'>"
            ]);
        }, $goods);
        echo json_encode($goods_with_img);
    }

    /**
     * Receives submitted WinCube goods via Ajax and adds them into DB
     */
    function ajax_wincube_import()
    {
        $this->output->set_content_type('json');

        $goods = json_decode($this->input->raw_input_stream, true);

        // Extract brands from WinCube products
        $brands = array_unique(array_column($goods, 'affiliate'));

        // Find already-imported WinCube brands
        $query = $this->db->get_where('tbl_businesses', ['source' => 'wincube']);
        $existing_brands = $query->result_array();
        $existing_brands_id_map = array_combine(
            array_column($existing_brands, 'name'),
            array_column($existing_brands, 'id')
        );

        // Insert new WinCube brands
        $new_brands = array_values(array_diff($brands, array_column($existing_brands, 'name')));
        $new_brands_id_map = [];
        if (count($new_brands) > 0) {
            $new_brands_rows = array_map(function ($name) {
                return [
                    'name' => $name,
                    'source' => 'wincube',
                    'image' => 'default.png'
                ];
            }, $new_brands);
            $this->db->insert_batch('tbl_businesses', $new_brands_rows);

            $start_id = $this->db->insert_id();
            $inserted_ids = range($start_id, $start_id + count($new_brands) - 1);
            $new_brands_id_map = array_combine($new_brands, $inserted_ids);

            $new_brand_country_links = array_map(function ($brand_id) {
                return [
                    'business_id' => $brand_id,
                    'gift_country_id' => 3
                ];
            }, $inserted_ids);
            $this->db->insert_batch('tbl_business_country', $new_brand_country_links);
        }
        $brands_id_map = array_merge($existing_brands_id_map, $new_brands_id_map);

        // Find already-imported WinCube products
        $query = $this->db
            ->from('tbl_gifticons')
            ->where_in('wincube_id', array_column($goods, 'goods_id'))
            ->get();
        $existing_goods = $query->result_array();

        // Insert new products with brand IDs
        $existing_goods_ids = array_column($existing_goods, 'wincube_id');
        $new_goods = array_filter($goods, function ($item) use ($existing_goods_ids) {
            return !in_array($item['goods_id'], $existing_goods_ids);
        });
        // echo json_encode(['existing_brands' => $existing_brands, 'new_brands' => $new_brands]); return;
        $new_goods_to_insert = array_map(function ($item) use ($brands_id_map) {
            return [
                'name' => $item['goods_nm'],
                'image' => 'default.png',
                'business_id' => (int)$brands_id_map[$item['affiliate']],
                'wincube_id' => $item['goods_id'],
                'wincube_image' => $item['goods_img'],
                'terms' => $item['desc'],
                'normal_price' => $item['normal_sale_price'] + $item['normal_sale_vat'],
                'coupon_price' => $item['total_price'],
                'sale_end_date' => date_format(date_create($item['period_end']), 'Y-m-d'),
            ];
        }, $new_goods);

        $this->db->db_debug = true;
        $this->db->insert_batch('tbl_gifticons', $new_goods_to_insert);
        echo json_encode(['affected_rows' => $this->db->affected_rows()]);
        // echo json_encode($new_goods_to_insert);
    }
    
    function insert()
    {
        $this->form_validation->set_rules('name', 'Name', 'required',array("required"=>"Please provide Name"));

        $data['page']="gift";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('gift/add_gift',$data);
        }
        else
        {
            $AllPostdata = $this->input->post();

            $fulldata = $this->input->post();
            $tags = array();

            extract($this->input->post());
            if(isset($AllPostdata['tags']) && $AllPostdata['tags'] != "")
            {
                $tags = $AllPostdata['tags'];    
            }
            

            $sizes = array();

            if(isset($AllPostdata['size_data']) && !empty($AllPostdata['size_data']))
            {
                $sizes = $AllPostdata['size_data'];

                $size_array = array();

                $AllPostdata['size'] = implode(",", array_map("trim",array_filter($sizes)));    
            }

            unset($AllPostdata['Small_price']);
            unset($AllPostdata['Regular_price']);
            unset($AllPostdata['Medium_price']);
            unset($AllPostdata['Large_price']);
            unset($AllPostdata['Small_price_coupon']);
            unset($AllPostdata['Regular_price_coupon']);
            unset($AllPostdata['Medium_price_coupon']);
            unset($AllPostdata['Large_price_coupon']);
            unset($AllPostdata['size_data']);
            unset($AllPostdata['tags']);



            if($AllPostdata['gifticon_type'] == 0)
            {
                if(isset($_FILES['image']['name']) && $_FILES['image']['size'] > 0)
                {
                    $image = $this->common->media_upload_S3($field = 'image',$path = "gift/");
                    if ($image) {
                        $AllPostdata['image'] = $image;
                    }else{
                        $AllPostdata['image'] = 'default.png';
                    }
                }else{
                    $AllPostdata['image'] = 'default.png';
                } 

               
                if(!isset($AllPostdata['coupon_price']) && $AllPostdata['coupon_price'] == '')
                {
                    $AllPostdata['coupon_price'] = $AllPostdata['normal_price'];
                }

                $this->db->insert('tbl_gifticons',$AllPostdata);
                $gift_id = $this->db->insert_id();

                $this->db->insert('tbl_gifticon_images',array('gifticon_id'=>$gift_id,'image'=>$AllPostdata['image']));

                $qdata = "gifticon_id=".$gift_id."&name=".$AllPostdata['name']."";

                $QRdata = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=".base64_encode($qdata)."";

                $this->db->update('tbl_gifticons',array('qr_code'=>$QRdata,'sequence'=>$gift_id),array('id'=>$gift_id));

                if(!empty($_FILES['pimages']['name']))
                {
                    $files = $_FILES;
                    $count = count($_FILES['pimages']['name']);
                    for($i=0; $i<$count; $i++)
                    {
                        $imagenm = $this->common->media_upload_S3_multiple($field = 'pimages',$file = $i,$path = "gift/");
                        if ($imagenm) {

                            $this->db->insert('tbl_gifticon_images',array('gifticon_id'=>$gift_id,'image'=>$imagenm));
                        }
                    }
                }

                if(!empty($sizes))
                {
                    foreach ($sizes as $key => $value) {
                        $param = array(
                            'gifticon_id'=>$gift_id,
                            'size'=>$value,
                            'price'=>$fulldata[trim($value).'_price'],
                            'coupon_price'=>$fulldata[trim($value).'_price_coupon']
                        );
                        $this->db->insert('tbl_gift_size',$param);
                    }    
                }

                if(!empty($tags))
                {
                    foreach ($tags as $key => $value) 
                    {
                        $this->db->insert('tbl_gifticons_tags',array('gifticon_id'=>$gift_id,'tag_id'=>$value));
                    }    
                }

                $this->session->set_flashdata("suc","Gifticon added successfully!");
                redirect("admin/gift/listing");    
            }
            else
            {
                
                $giftformate = $AllPostdata['giftcard_format'];

                if(isset($_FILES['image']['name']) && $_FILES['image']['size'] > 0)
                {
                    $image = $this->common->media_upload_S3($field = 'image',$path = "gift/");
                    if ($image) {
                        $AllPostdata['image'] = $image;
                    }else{
                        $AllPostdata['image'] = 'default.png';
                    }
                }else{
                    $AllPostdata['image'] = 'default.png';
                } 

               
                if(!isset($AllPostdata['coupon_price']) && $AllPostdata['coupon_price'] == '')
                {
                    $AllPostdata['coupon_price'] = $AllPostdata['normal_price'];
                }

                $this->db->insert('tbl_gifticons',$AllPostdata);
                $gift_id = $this->db->insert_id();

                $this->db->insert('tbl_gifticon_images',array('gifticon_id'=>$gift_id,'image'=>$AllPostdata['image']));

                $this->db->update('tbl_gifticons',array('sequence'=>$gift_id),array('id'=>$gift_id));

                if(!empty($_FILES['pimages']['name']))
                {
                    $files = $_FILES;
                    $count = count($_FILES['pimages']['name']);
                    for($i=0; $i<$count; $i++)
                    {
                        $imagenm = $this->common->media_upload_S3_multiple($field = 'pimages',$file = $i,$path = "gift/");
                        if ($imagenm) {

                            $this->db->insert('tbl_gifticon_images',array('gifticon_id'=>$gift_id,'image'=>$imagenm));
                        }
                    }
                }

                if(!empty($sizes))
                {
                    foreach ($sizes as $key => $value) {
                        $param = array(
                            'gifticon_id'=>$gift_id,
                            'size'=>$value,
                            'price'=>$fulldata[trim($value).'_price'],
                            'coupon_price'=>$fulldata[trim($value).'_price_coupon']
                        );
                        $this->db->insert('tbl_gift_size',$param);
                    }    
                }

                if(!empty($tags))
                {
                    foreach ($tags as $key => $value) 
                    {
                        $this->db->insert('tbl_gifticons_tags',array('gifticon_id'=>$gift_id,'tag_id'=>$value));
                    }    
                }

                if(!empty($_FILES["file"]["size"]))     
                {
                    $file_name=$_FILES['file']['name'];
                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    
                    if($ext=="csv" || $ext=="CSV")
                    {
                        $final_data=[];
                        if($_FILES["file"]["size"] > 0)
                        {
                            $flag=FALSE;
                            $filename = $_FILES["file"]["tmp_name"];
                            
                            $file = fopen($filename, "r");

                            while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                            {
                                $data=$emapData;
                                $length_data=count($data);
                                if($length_data>1)
                                {
                                    if($data[0]!=NULL)
                                    {
                                        $plaincode = $data[0];
                                        $QRdata = "";
                                         
                                        $AllPostdata=array(
                                            "plain_code"=>$plaincode,
                                            "pin"=>($data[1]==NULL)? '':$data[1],
                                        );
                                        array_push($final_data, $AllPostdata);
                                    }  
                                }
                            }
                            
                            $length=count($final_data);
                            $insertdata=[];
                            if($length>0)
                            {
                                $AllPostdata = $this->input->post();
                                $paramg = array();
                                foreach ($final_data as $key => $value) {
                                    $paramg['gifticon_id'] = $gift_id;
                                    $paramg['code'] = $value['plain_code'];
                                    $paramg['notes'] = ($value['pin'] != "") ? $value['pin'] : "";

                                    if($key != 0)
                                    {
                                        $this->db->insert('tbl_giftcards',$paramg);    
                                    }
                                }

                                 $this->session->set_flashdata("suc","data import successfully!");
                                 redirect("admin/gift/listing");
                            }else{
                                $this->session->set_flashdata("msg","File is Empty!");
                                redirect("admin/gift/listing");        
                            }
                        }
                
                        }
                        else
                        {
                            $this->session->set_flashdata("msg","Please Select only .csv file for import data!");
                            redirect("admin/gift/listing");
                        }
                }
                else
                {
                    $this->session->set_flashdata("msg","File is Empty!");
                    redirect("admin/gift/listing");
                }
            }
        }
    }
   

    function giftEdit($id)
    {
        $gdata = $this->my_model->get_one_record($tbl_name="tbl_gifticons",$id);
        $data['gift']= $gdata;
        $data['page']="gift";
        $data['business'] = $this->db->get_where('tbl_businesses',array('is_delete'=>0))->result_array();
        $data['tags'] = $this->db->get_where('tbl_tags')->result_array();
        $data['country'] = $this->db->get_where('tbl_gift_country')->result_array();
        $data['sizes'] = array('Small','Regular','Medium','Large');
        $data['gift_size'] = $this->db->get_where('tbl_gift_size',array('gifticon_id'=>$id))->result_array();
        $data['gift_images'] = $this->db->get_where('tbl_gifticon_images',array('gifticon_id'=>$id))->result_array();

        $yy = $this->db->get_where('tbl_gifticons_tags',array('gifticon_id'=>$id))->result_array();
        $fdata = array();
        if($yy)
        {
        	foreach ($yy as $key => $value) {
        	$fdata[] = $value['tag_id'];
        	}	
        }
        $sizedata = array();

        if($gdata['size'] != '')
        {
            $sizedata = explode(',', $gdata['size']);
        }
        $data['sizedata']= $sizedata;
        
        $data['gifticons_tags'] = $fdata; 
        $this->load->view('gift/gift_update',$data);
    }
    
    function update()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');

        $data['page']="gift";
        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('gift/gift_update',$data);
        }
        else
        {
        	$AllPostdata= $this->input->post();

            $fulldata = $this->input->post();

            $tags = array();
            
            if(isset($AllPostdata['tags']) && !empty($AllPostdata['tags']))
            {
                $tags = $AllPostdata['tags'];
            }    

            $gift_id = $AllPostdata['gift_id'];

            $giftde = $this->db->get_where('tbl_gifticons',array('id'=>$gift_id))->row_array();
            if(isset($AllPostdata['size_data']) && !empty($AllPostdata['size_data']))
            {
                $sizes = $AllPostdata['size_data'];

                $size_array = array();

                $AllPostdata['size'] = implode(",", array_map("trim",array_filter($sizes)));    
            }

            unset($AllPostdata['Small_price']);
            unset($AllPostdata['Regular_price']);
            unset($AllPostdata['Medium_price']);
            unset($AllPostdata['Large_price']);
            unset($AllPostdata['Small_price_coupon']);
            unset($AllPostdata['Regular_price_coupon']);
            unset($AllPostdata['Medium_price_coupon']);
            unset($AllPostdata['Large_price_coupon']);
            unset($AllPostdata['tags']);
            unset($AllPostdata['gift_id']);
            unset($AllPostdata['size_data']);

            if(isset($_FILES['image']['name']) && $_FILES['image']['size'] > 0)
            {
                $image = $this->common->media_upload_S3($field = 'image',$path = "gift/");
                if ($image) {
                    $AllPostdata['image'] = $image;

                    $this->db->update('tbl_gifticon_images',array('image'=>$image),array('gifticon_id'=>$gift_id,'image'=>$giftde['image']));
                }
            }

            if(!empty($_FILES['pimages']['name']))
            {
                $files = $_FILES;
                $count = count($_FILES['pimages']['name']);
                for($i=0; $i<$count; $i++)
                {

                    $imagenm = $this->common->media_upload_S3_multiple($field = 'pimages',$file = $i,$path = "gift/");
                    if ($imagenm) {

                        $this->db->insert('tbl_gifticon_images',array('gifticon_id'=>$gift_id,'image'=>$imagenm));
                    }
                }
            }

        	$this->db->update('tbl_gifticons',$AllPostdata,array('id'=>$gift_id));
            
            

            if(!empty($tags))
            {
                $this->db->delete('tbl_gifticons_tags',array('gifticon_id'=>$gift_id));
                foreach ($tags as $key => $value) 
                {
                    $this->db->insert('tbl_gifticons_tags',array('gifticon_id'=>$gift_id,'tag_id'=>$value));
                }    
            }

			if(!empty($_FILES["file"]["size"]))     
            {
                $file_name=$_FILES['file']['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                if($ext=="csv" || $ext=="CSV")
                {
                    $final_data=[];
                    if($_FILES["file"]["size"] > 0)
                    {
                        $flag=FALSE;
                        $filename = $_FILES["file"]["tmp_name"];
                        
                        $file = fopen($filename, "r");

                        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
                        {
                            $data=$emapData;
                            $length_data=count($data);
                            if($length_data>1)
                            {
                                if($data[0]!=NULL)
                                {
                                    $plaincode = $data[0];
                                    $QRdata = "";
                                     
                                    $newdata=array(
                                        "plain_code"=>$plaincode,
                                        "pin"=>($data[1]==NULL)? '':$data[1],
                                    );
                                    array_push($final_data, $newdata);
                                }  
                            }
                        }
                       
                        $length=count($final_data);
                        $insertdata=[];
                        if($length>0)
                        {
                            $paramg = array();
                            foreach ($final_data as $key => $value) {
                                $paramg['gifticon_id'] = $gift_id;
                                $paramg['code'] = $value['plain_code'];
                                $paramg['notes'] = ($value['pin'] != "") ? $value['pin'] : "";

                                if($key != 0)
                                {
                                    $this->db->insert('tbl_giftcards',$paramg);    
                                }
                            }
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata("msg","Please Select only .csv file for import data!");
                    redirect("admin/gift/giftEdit/".$gift_id);
                }
            }

            if(!empty($sizes))
            {
                $this->db->delete('tbl_gift_size',array('gifticon_id'=>$gift_id));

                foreach ($sizes as $key => $value) {
                    $param = array(
                        'gifticon_id'=>$gift_id,
                        'size'=>$value,
                        'price'=>$fulldata[trim($value).'_price'],
                        'coupon_price'=>$fulldata[trim($value).'_price_coupon']
                    );
                    $this->db->insert('tbl_gift_size',$param);
                }    
            }

			$this->session->set_flashdata("suc","Update gifticon successfully!");
            redirect("admin/gift/listing/1");
            //redirect("admin/gift/giftEdit/".$gift_id);
        }
    }
    public function checkCategoryExists($name,$id=null)
    {
        $data=$this->gift_model->checkCategoryExists($name,$id);
        if($data){
            return false;
        }else{
            return true;
        }  
    }
    public function Delete()
    {
        $id=$_POST['id'];

        $this->db->update('tbl_gifticons',array('is_delete'=>'1'),array('id'=>$id));
	}

    public function active_inactive(){
        $id=$_POST['id'];
        $tbl_name=$_POST['tbl_name'];
        $this->my_model->active_inactive($id,$tbl_name);
    }

    private function set_barcode($code)
    {
        return $imageResource = Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
    }

    function deleteImage($img_id,$gift_id)
    {
        $this->db->delete('tbl_gifticon_images',array('id'=>$img_id));

        $this->session->set_flashdata("suc","Image has been deleted!");
        redirect("admin/gift/giftEdit/".$gift_id);
    }

     function purchased($gift_id)
    {
        $data['page']="gift";
        $data['gift_id'] = $gift_id;
        $this->load->view('gift/purchase_list',$data);
    }

    function purchase_ajax_list($gift_id)
    {
        
        $category_list = $this->gift_model->getPurchase($gift_id);
        $data = array();
        if(!empty($category_list)) 
        {
            foreach ($category_list as $key => $category) {
                $row = array();
                $row['id'] = $key+1;
                $row['username'] = $category['username'];
                $row['gift_name'] = $category['gift_name'];
                $row['business_name'] = $category['business_name'];
                $row['scanner_id'] = $category['scanner_id'];
                $row['gift_image'] = '<img class="img-responsive img-circle img-thumbnail thumb-md" src='.GIFT_IMAGE.$category['gift_image'].' height="60" width="60"  alt="No image">';
                
                 if($category['gifticon_type'] == 0)
                {
                    $row['gifticon_type'] = 'gifticon';
                    $row['giftcard_format'] = 'QR Code <br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="QR">';
                }else{
                    $row['gifticon_type'] = 'giftcard';
                }
                
                if($category['giftcard_format'] == 0)
                {
                    $row['giftcard_format'] = 'Plain Code: '.$category['plain_code'].'<br>'. 'Pin : '.$category['pin'];
                }/*elseif($category['giftcard_format'] == 1)
                {
                    $row['giftcard_format'] = 'QR Code <br> <img class="img-responsive img-thumbnail thumb-md" src='.$category['qr_code'].' height="40" width="40"  alt="QR">';
                }*/elseif($category['giftcard_format'] == 2)
                {
                    $row['giftcard_format'] = 'Barcode string : '.$category['plain_code'].'<br>'. 'Pin : '.$category['pin'];
                }

                $row['normal_price'] = $category['normal_price'];

                $row['price'] = $category['price'];

                $row['coupon_discount_amount'] = $category['coupon_discount_amount'];

                $row['is_redeem'] = ($category['is_redeem'] == '1') ? "Yes" : "No";

                $row['purchase_date'] = $category['purchase_date'];

                $row['redeem_date'] = $category['redeem_date'];

                $row['giftto_user_name'] = $category['giftto_user_name'];

                $row['giftfrom_user_name'] = $category['giftfrom_user_name'];
                $row['currency'] = $category['currency'] ? strtoupper($category['currency']) : '-';

                $data[] = $row;
            }
        }
        $output = array(
            "total" => $this->gift_model->count_filtered_purchase($gift_id),
            "rows" => $data
            );
        echo json_encode($output);
    }

    function check_qty()
    {
    	// $dataId=$_POST['dataId'];
        // $client = new GuzzleHttp\Client();
        // $res = $client->request('POST', WINCUBE_API_BASE . 'check_goods.do', [
        //     'query' => [
        //         'mdcode' => 'gifticon_nz',
        //         'goods_id' => $dataId,
        //         'response_type' => 'JSON'
        //     ]
        // ]);
        // $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
        // $goods = json_decode($body, true);
        // var_dump($goods);

        $msile = include APPPATH.'/config/wincube_error_code.php';

        $client = new GuzzleHttp\Client();
        $payload = [
            'query' => [
                'mdcode' => 'gifticon_nz',
                'response_type' => 'JSON',
                'msg' => mb_convert_encoding('해외에서 마음이 담긴 선물이 도착했습니다! 사랑이 담긴 선물을 확인해주세요 :D [Global Gifticon : 글로벌 선물하기 서비스]', 'EUC-KR', 'UTF-8'),
                'title' => mb_convert_encoding('선물과 함께 예쁜 하루 보내세요. 먼 곳에서도 응원할게요', 'EUC-KR', 'UTF-8'),
                'callback' => '09798179261',
                'goods_id' => 'G00000028859',
                'phone_no' => '09798179261',
                'tr_id' => 22866
            ]
        ];
        $res = $client->request('POST', WINCUBE_API_BASE . 'request.do', $payload);
        $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
        $voucher_issue_result = json_decode($body, true);
        
        $resposne_reason = $voucher_issue_result['result_code'];
        var_dump($msile[$resposne_reason]);
        // $this->db->update('tbl_purchases', ['response_reason' => $resposne_reason], ['id' => 22863]);
        var_dump($voucher_issue_result);
    }

    function wincubeDataGoodsSync()
    {
    	$testData=$_POST['testData'];

        if($testData == "Wincube_test")
        {
            $this->output->set_content_type('json');
    
            $client = new GuzzleHttp\Client();
            $res = $client->request('POST', WINCUBE_API_BASE . 'salelist.do', [
                'query' => [
                    'mdcode' => 'gifticon_nz',
                    'response_type' => 'JSON'
                ]
            ]);
            $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
            $goods = json_decode($body, true)['goods_list'];
            if (empty($goods)) {
                echo json_encode(['message' => 'Something was wrong querying WinCube', 'wincube_response' => $body]);
                die;
            }
            $goods_with_img = array_map(function ($item) {
                return array_merge($item, [
                    'goods_img_html' => "<img class='img-responsive img-circle img-thumbnail thumb-md' src='{$item['goods_img']}' height='60' width='60'>"
                ]);
            }, $goods);
            $data = $goods_with_img;

            var_dump($data);
            exit;

        }elseif($testData == "")
        {
            $data = file_get_contents(__DIR__.'/SampleJson.json');
        }
        else{
            $data = $testData;
        }
        $goods = json_decode($data, true);
        // $this->output->set_content_type('json');

        // $goods = json_decode($this->input->raw_input_stream, true);

        // Extract brands from WinCube products
        $brands = array_unique(array_column($goods, 'affiliate'));

        // Find already-imported WinCube brands
        $query = $this->db->get_where('tbl_businesses', ['source' => 'wincube']);
        $existing_brands = $query->result_array();
        $existing_brands_id_map = array_combine(
            array_column($existing_brands, 'name'),
            array_column($existing_brands, 'id')
        );

        // Insert new WinCube brands
        $new_brands = array_values(array_diff($brands, array_column($existing_brands, 'name')));

        $new_brands_id_map = [];
        if (count($new_brands) > 0) {
            $new_brands_rows = array_map(function ($name) {
                return [
                    'name' => $name,
                    'source' => 'wincube',
                    'image' => 'default.png'
                ];
            }, $new_brands);
            $this->db->insert_batch('tbl_businesses', $new_brands_rows);

            $start_id = $this->db->insert_id();
            $inserted_ids = range($start_id, $start_id + count($new_brands) - 1);
            $new_brands_id_map = array_combine($new_brands, $inserted_ids);

            $new_brand_country_links = array_map(function ($brand_id) {
                return [
                    'business_id' => $brand_id,
                    'gift_country_id' => 3
                ];
            }, $inserted_ids);
            $this->db->insert_batch('tbl_business_country', $new_brand_country_links);
        }
        $brands_id_map = array_merge($existing_brands_id_map, $new_brands_id_map);

        // Find already-imported WinCube products
        $query = $this->db
            ->from('tbl_gifticons')
            ->where_in('wincube_id', array_column($goods, 'goods_id'))
            ->get();
        $existing_goods = $query->result_array();

        // Insert new products with brand IDs
        $existing_goods_ids = array_column($existing_goods, 'wincube_id');

        $new_goods = array_filter($goods, function ($item) use ($existing_goods_ids) {
            return !in_array($item['goods_id'], $existing_goods_ids);
        });

        $query = $this->db
        ->from('tbl_gifticons')
        ->select('wincube_id')
        ->where('wincube_id is NOT NULL', NULL, FALSE)
        ->get();
        $all_existing_goods = $query->result_array();
        $all_existing_good_ids = array_column($all_existing_goods, 'wincube_id');
        $import_good_ids = array_column($goods, 'goods_id');
        $not_included_goods = array_diff($all_existing_good_ids, $import_good_ids);

        //update out of stock items
        if(count($not_included_goods) > 0)
        {
            $not_included_goods_to_update = array_map(function ($item) {
                return [
                    'wincube_id' => $item,
                    'is_active' => 0,
                    'is_delete' => 0,
                    'terms' => "out_stock",
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $not_included_goods);
            $this->db->update_batch('tbl_gifticons', $not_included_goods_to_update, 'wincube_id');
        }

        $existing_good_datas = array_filter($goods, function ($item) use ($existing_goods_ids) {
            return in_array($item['goods_id'], $existing_goods_ids);
        });

        if(count($new_goods) > 0)
        {
            $new_goods_to_insert = array_map(function ($item) use ($brands_id_map) {
                return [
                    'name' => $item['goods_nm'],
                    'image' => 'default.png',
                    'business_id' => (int)$brands_id_map[$item['affiliate']],
                    'wincube_id' => $item['goods_id'],
                    'wincube_image' => $item['goods_img'],
                    'terms' => "new_item",
                    'normal_price' => $item['normal_sale_price'] + $item['normal_sale_vat'],
                    'coupon_price' => $item['total_price'],
                    'sale_end_date' => date_format(date_create($item['period_end']), 'Y-m-d'),
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $new_goods);
            $this->db->db_debug = true;
            $this->db->insert_batch('tbl_gifticons', $new_goods_to_insert);
            // echo json_encode(['affected_rows new' => $this->db->affected_rows()]);
        }

        if(count($existing_good_datas) > 0)
        {
            $existing_goods_to_insert = array_map(function ($item) use ($brands_id_map) {
                return [
                    'name' => $item['goods_nm'],
                    'image' => 'default.png',
                    'business_id' => (int)$brands_id_map[$item['affiliate']],
                    'wincube_id' => $item['goods_id'],
                    'wincube_image' => $item['goods_img'],
                    'terms' => "ex_item",
                    'normal_price' => $item['normal_sale_price'] + $item['normal_sale_vat'],
                    'coupon_price' => $item['total_price'],
                    'sale_end_date' => date_format(date_create($item['period_end']), 'Y-m-d'),
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $existing_good_datas);
            $this->db->db_debug = true;
            $this->db->update_batch('tbl_gifticons', $existing_goods_to_insert, 'wincube_id');
            // echo json_encode(['affected_rows existing' => $this->db->affected_rows()]);
        }

        // echo "existing_goods_to_insert" . count($existing_goods_to_insert);
        // echo "new_goods" . count($new_goods);
        // echo "not_included_goods" . count($not_included_goods);

        
        echo json_encode([
            'existing_goods_to_insert' => count($existing_goods_to_insert),
            'new_goods' => count($new_goods),
            'not_included_goods' => count($not_included_goods),
        ]);

    }

}
?>