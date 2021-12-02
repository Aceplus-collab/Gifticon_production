<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{   
		parent::__construct();
	}

	public function index()
	{
		$data['pagename'] = "index";
		$this->load->view('index',$data);
	}

	function privacy()
	{
		$this->load->view('privacy');
	}

	function faq()
	{
		$this->load->view('faq');
	}

	function aboutus()
	{
		$this->load->view('aboutus');
	}

	function load()
	{
		$this->load->view('load');
	}

	function testsend()
    {
			$apnsHost = 'api.sandbox.push.apple.com';
			$apnsCert = 'Development.pem';
			$apnsPort = 443;
			$apnsPass = 'hyperlink';
			$token = '48C6588A116F047E9F49247CA9B84E0B5AA5BAEDAE348D25D3E3E3DE10BB434C';

			$payload['aps'] = array('alert' => 'hello', 'badge' => 1, 'sound' => 'default');
			$output = json_encode($payload);
			$token = pack('H*', str_replace(' ', '', $token));
			$apnsMessage = chr(0).chr(0).chr(32).$token.chr(0).chr(strlen($output)).$output;

			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
			stream_context_set_option($streamContext, 'ssl', 'passphrase', $apnsPass);

			$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
			fwrite($apns, $apnsMessage);
			fclose($apns);
    }

    function test()
    {
				$keyfile = 'AuthKey_FCUPCPXU6H.p8';               # <- Your AuthKey file
				$keyid = 'FCUPCPXU6H';                            # <- Your Key ID
				$teamid = '66H7Z5943S';                           # <- Your Team ID (see Developer Portal)
				$bundleid = 'com.pointzero.gifticon';                # <- Your Bundle ID
				$url = 'https://api.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
				$token = '858B5A7CB0E0C5D2C46B18CBB803E1018A6929DCBBCD9BB01552250E9D08CD28';              # <- Device Token

				$message = '{"aps":{"alert":"Hi birva!","sound":"default"}}';

				$key = openssl_pkey_get_private('file://'.$keyfile);

				$header = ['alg'=>'ES256','kid'=>$keyid];
				$claims = ['iss'=>$teamid,'iat'=>time()];

				$header_encoded = $this->base64($header);
				$claims_encoded = $this->base64($claims);

				$signature = '';
				openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
				$jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);

				// only needed for PHP prior to 5.5.24
				if (!defined('CURL_HTTP_VERSION_2_0')) {
				define('CURL_HTTP_VERSION_2_0', 3);
				}

				$http2ch = curl_init();
				curl_setopt_array($http2ch, array(
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
				CURLOPT_URL => "$url/3/device/$token",
				CURLOPT_PORT => 443,
				CURLOPT_HTTPHEADER => array(
				"apns-topic: {$bundleid}",
				"authorization: bearer $jwt"
				),
				CURLOPT_POST => TRUE,
				CURLOPT_POSTFIELDS => $message,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HEADER => 1
				));

				$result = curl_exec($http2ch);
				print_r($result);
				if ($result === FALSE) {
				throw new Exception("Curl failed: ".curl_error($http2ch));
				}

				$status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
				echo $status;

				
    }

    function base64($data) {
		return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
	}

	function update()
	{
		$data = $this->db->get_where('tbl_gifticons')->result_array();

		foreach ($data as $key => $value) 
		{
			$this->db->insert('tbl_gifticon_images',array('gifticon_id'=>$value['id'],'image'=>$value['image']));
		}
	}

	
}
?>