<?php (defined('BASEPATH'));

class GiftCronController extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('my_model');
        $this->load->model('gift_model');
    }

    public function wincubeSync()
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
        $goods = $data;

        // Extract brands from WinCube products
        $brands = array_unique(array_column($goods, 'affiliate'));

        // Find already-imported WinCube brands
        $query = $this->db->get_where('tbl_businesses', ['source' => 'wincube']);
        $existing_brands = $query->result_array();
        $existing_brands_id_map = array_combine(
            array_column($existing_brands, 'name'),
            array_column($existing_brands, 'id')
        );

        //Find already imported brands list
        $query = $this->db
            ->from('tbl_businesses')
            ->where_in('name', $brands)
            ->where('source', 'wincube')
            ->get();
        $existing_brand_list = $query->result_array();

        if(count($existing_brand_list) > 0)
        {
            $existing_brands_rows = array_map(function ($name) {
                return [
                    'id' => $name['id'],
                    'source' => 'wincube',
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $existing_brand_list);

            $this->db->db_debug = true;
            $this->db->update_batch('tbl_businesses', $existing_brands_rows, 'id');
        }

        // Insert new WinCube brands
        $new_brands = array_values(array_diff($brands, array_column($existing_brands, 'name')));

        $new_brands_id_map = [];
        if (count($new_brands) > 0) {
            $new_brands_rows = array_map(function ($name) {
                return [
                    'name' => $name,
                    'source' => 'wincube',
                    'image' => 'default.png',
                    'is_active' => 1,
                    'update_date' => date('Y-m-d h:i:s')
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
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $not_included_goods);
            $this->db->update_batch('tbl_gifticons', $not_included_goods_to_update, 'wincube_id');
        }

        $existing_good_datas = array_filter($goods, function ($item) use ($existing_goods_ids) {
            return in_array($item['goods_id'], $existing_goods_ids);
        });

        //import new wincube product
        if(count($new_goods) > 0)
        {
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
                    'is_active' => 1,
                    'sale_end_date' => date_format(date_create($item['period_end']), 'Y-m-d'),
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $new_goods);
            $this->db->db_debug = true;
            $this->db->insert_batch('tbl_gifticons', $new_goods_to_insert);
        }

        //update existing wincube product
        if(count($existing_good_datas) > 0)
        {
            $existing_goods_to_insert = array_map(function ($item) use ($brands_id_map) {
                return [
                    'name' => $item['goods_nm'],
                    'image' => 'default.png',
                    'business_id' => (int)$brands_id_map[$item['affiliate']],
                    'wincube_id' => $item['goods_id'],
                    'wincube_image' => $item['goods_img'],
                    'terms' => $item['desc'],
                    'normal_price' => $item['normal_sale_price'] + $item['normal_sale_vat'],
                    'coupon_price' => $item['total_price'],
                    'is_active' => 1,
                    'sale_end_date' => date_format(date_create($item['period_end']), 'Y-m-d'),
                    'update_date' => date('Y-m-d h:i:s')
                ];
            }, $existing_good_datas);
            $this->db->db_debug = true;
            $this->db->update_batch('tbl_gifticons', $existing_goods_to_insert, 'wincube_id');
        }

        
        echo json_encode([
            'new_goods' => count($new_goods),
            'existing_goods_to_insert' => count($existing_goods_to_insert),
            'not_included_goods' => count($not_included_goods),
            'new_brands' => count($new_brands),
            'existing_brand_list' => count($existing_brand_list)
        ]);
    }

}
?>