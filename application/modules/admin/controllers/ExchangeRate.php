<?php (defined('BASEPATH'));

class ExchangeRate extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }
    public function exchangeRateSave()
    {
        //exchange rate update
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', EXCHANGE_RATE_API);
        $body = mb_convert_encoding($res->getBody(), 'UTF-8', 'EUC-KR');
        $goods = json_decode($body, true);
        $this->db->truncate('tbl_exchange_rate'); 
        foreach($goods['conversion_rates'] as $key => $val)
        {
            $this->db->insert('tbl_exchange_rate',array('ex_country'=>$key,'rate'=>$val));
        }
    }

}
?>