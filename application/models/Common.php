<?php
//ini_set('memory_limit', '1024M');
use AWS\S3\Exception\S3Exception; 
use Aws\S3\S3Client;
require APPPATH .'libraries/app/s3config.php';

require_once('inc_jwt_helper.php');

class Common extends s3config
{
    public $language='english';

	public function __construct()
    {
        parent::__construct();

        $this->encrypted_method = array("uploadGif","uploadImage", "uploadMultipleMedia","uploadMultipleMediaReviewImage","uploadMediaReviewVideo");
        
        $this->s3  = S3Client::factory([
                'region'            => 'eu-west-1', //  //  eu-west-1 // southeast-2
                'version'           => '2006-03-01',
                'signature_version' => 'v4',
                'credentials' => [
                    'key'    => $this->s3val['s3']['key'],
                    'secret' => $this->s3val['s3']['secret'],
                ]
        ]);

        $this->valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP","mov", "mpg", "flv","webm", "mp4", "ogv", "3gp", "mkv","m4v");
    }

    /**
     * Function is used to validate header while new request coming for new call
     */

     function validate_header_token($response_obj)
    {
        $language = "english";
        if(!empty($this->input->get_request_header("language",TRUE)))
        {
           // echo $this->input->get_request_header("language",TRUE); die;
            $language = $this->input->get_request_header("language",TRUE);    
        }             
        if(!in_array($this->language, array("english","korean","chinese","japanese")))
        {
            $this->language="english";
        }else{
              $this->language= $language;
        }
        //echo  $this->language; die;
        $methodname = $this->router->fetch_method();
        if (!in_array($methodname, $this->encrypted_method)) {
            $this->post_body_decrypt();
        }
        $method_array = array("signup","login","forgot_password","checkSocial","getCountry","qrScan","getHomecount","uploadGif");
        if(!in_array($methodname, $method_array))
        {
            
            $tokent = $this->input->get_request_header("token");
           
            $token = $this->AES_decrypt($tokent);

           
            if($token != "valarmorgulis")
            {
                $user_token_count = $this->db->get_where('tbl_user',array('token'=>$token))->num_rows();
                if(
                    (
                        (empty($token) || !isset($token) || $token=="") || ( ($user_token_count <= 0 && $user_token_count <= 0 ) )
                        )
                    )
                {   

                    $business_token_count = $this->db->get_where('tbl_businesses',array('token'=>$token))->num_rows();

                    if($business_token_count <= 0)
                    {
                        $message = ['code' => '0', 'message' => 'unauthorized token'];
                        $response_obj->response($message, REST_Controller::HTTP_UNAUTHORIZED);    
                    }

                }else{
                   // echo "okay";
                }                
            }
        }
    }
    

    /**
     * Body decryption
     */
    public function post_body_decrypt()
    {

        $_POST = array();
        $contents = file_get_contents('php://input');

        $params = (array) json_decode(trim($this->AES_decrypt($contents)), true);
        
        foreach ($params as $key => $value) {
            if ($value != '') {
                $_POST[$key] = $value;
            }
        }
    }

    /**
     *  AES encryption
     */
    public function AES_encrypt($plaintext)
    {
        $plaintext=trim($plaintext);
        $encryptionMethod = 'AES-256-CBC';
        $secret = hash('sha256', KEY_256);
        $encrypt_value = openssl_encrypt($plaintext, $encryptionMethod, $secret, 0, IV);

        return $encrypt_value;
    }

    /**
     * AES Descryption
     */
    public function AES_decrypt($ciphertext)
    {
        $encryptionMethod = 'AES-256-CBC';
        $secret = hash('sha256', KEY_256);
        $decrypt_value = openssl_decrypt($ciphertext, $encryptionMethod, $secret, 0, IV);

        return $decrypt_value;
    }

    /*
     *  Function is used add prefix in Number
     */

    function addOrdinalNumberSuffix($num) 
    {
    	if(!in_array(($num % 100),array(11,12,13)))
    	{
    		switch ($num % 10) 
    		{
    			case 1:  return $num.'st';
    			case 2:  return $num.'nd';
    			case 3:  return $num.'rd';
    		}
    	}
    	return $num.'th';
    }

    function generateToken(){
    	return strtotime(date("Ymd")).random_string('alnum',24).strtotime(date("his"));
    }

    
    function generateRandomString($length = 8) {
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
    		$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
    }

   
    function sendMail($mailConfig,$message)
    {
        // Load PHPMailer library
        $this->load->library('phpmailer_lib');

        // PHPMailer object
        $mail = $this->phpmailer_lib->load();

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = "ssl://smtp.googlemail.com";
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = false;
        $mail->Username = "aungthiha9885@gmail.com";
        $mail->Password = "phippernaghtihvd";
        $mail->SMTPSecure = "ssl";
        $mail->Port     = 465;

        // $config = Array(
        //     'protocol' => 'smtp',
        //     'smtp_host' => 'ssl://smtp.googlemail.com',
        //     'smtp_port' => 465,
        //     'smtp_user' => 'info@gifticonofficial.com', // phpsyshyperlink@gmail.com
        //     'smtp_pass' => 'Rlatkddnjs9555!', // phpsys1122
        //     'mailtype'  => 'html', 
        //     'charset'   => 'iso-8859-1'
        // );

        $mail->setFrom('aungthiha9885@gmail.com', $mailConfig['subject']);
        // $mail->addReplyTo('info@example.com', 'CodexWorld');

        // Add a recipient
        $mail->addAddress($mailConfig['to']);

        // Email subject
        $mail->Subject = $mailConfig['subject'];

        // Set email format to HTML
        $mail->isHTML(true);

        // Email body content
        $mailContent = $message;
        $mail->Body = $mailContent;

        // Send email
        if(!$mail->send()){
            return false;
        }else{
            return true;
        }

        // $this->load->library("email");
        // $config = Array(
        //     'protocol' => 'smtp',
        //     'smtp_host' => 'ssl://smtp.googlemail.com',
        //     'smtp_port' => 465,
        //     'smtp_user' => 'info@gifticonofficial.com', // phpsyshyperlink@gmail.com
        //     'smtp_pass' => 'Rlatkddnjs9555!', // phpsys1122
        //     'mailtype'  => 'html', 
        //     'charset'   => 'iso-8859-1'
        // );
        // $this->load->library('email', $config);
        // $this->email->set_newline("\r\n");
        // $this->email->initialize($config);
        // $this->email->from($mailConfig['from'], $mailConfig['subject']);
        // $this->email->to($mailConfig['to']); // 
        // $this->email->subject($mailConfig['subject']);
        // $this->email->set_header('MIME-Version', '1.0');
        // $this->email->set_header('X-Priority', '3');
        // $this->email->message($message);
        // if($this->email->send()){
        // 	return true;
        // } else {
        //     return false;
        // }
    }
	/*function sendMail($mailConfig,$message){

    	$this->load->library("email");
    	$config['protocol'] = 'sendmail';
    	$config['mailpath'] = '/usr/sbin/sendmail';
    	$config['mailtype'] = 'html';
    	$config['charset'] = 'UTF-8';
    	$config['wordwrap'] = TRUE;

    	$this->email->initialize($config);
    	$this->email->from($mailConfig['from'], $mailConfig['subject']);
    	$this->email->to($mailConfig['to']);
    	$this->email->subject($mailConfig['subject']);

    	$this->email->set_header('MIME-Version', '1.0');
    	$this->email->set_header('X-Priority', '3');

    	$this->email->message($message);
    	/* Sending mail *//*
    	if($this->email->send())
    		return true;
    	else
    		return false;
    }*/
    
  

    public function uploadImage($config, $is_multiple = "0", $request_data = array())
    {
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        /**
         * Condition is used to check is multiple file or not
         */
        if ($is_multiple == "0") {
            if ($this->upload->do_upload("image")) {
                $disp_image = $this->upload->data();
                $uploadedImage = $disp_image['file_name'];
                $config['create_thumb'] = false;
                $source_image = $config['upload_path'] . $uploadedImage;
                $new_image = $config['upload_path'] . 'thumb/';
                $width = "500";
                $height = "500";
                $this->create_thumb($source_image, $new_image, $height, $width);
                $message = ["code" => "1", 'message' => "Images uploaded successfulle", "image" => $uploadedImage];
                return $message;
            } else {
                $message = ["code" => "0", 'message' => $this->upload->display_errors("", "")];
                return $message;
            }
        } else {
            $files_list = array();


            $video_thumb = array();
            for ($i = 0; $i < count($_FILES['image']); $i++) {
                $_FILES["image"]['name'] = $_FILES['image']['name'][$i];
                $_FILES["image"]['type'] = $_FILES['image']['type'][$i];
                $_FILES["image"]['tmp_name'] = $_FILES['image']['tmp_name'][$i];
                $_FILES["image"]['error'] = $_FILES['image']['error'][$i];
                $_FILES["image"]['size'] = $_FILES['image']['size'][$i];
                
                if ($this->upload->do_upload('image')) {
                    $disp_image = $this->upload->data();
                    $uploadedImage = $disp_image['file_name'];
                    $media_type = ($disp_image['is_image'] == "1") ? "I" : "V";
                    /**
                     * condition is to check for what api file is uploading and preparing array according to that
                     */
                    
                        $files_list[] = array("image" => $uploadedImage, "video_thumb" => "", "media_type" => $media_type);
                } else {
                    $message = ['code' => "0", 'message' => $this->upload->display_errors("", "")];
                    return $message;
                }
            }
            /**
             * Condition is used to check whether video thunb is coming or not
             */
            if (isset($_FILES['video_thumb']['name']) && count($_FILES['video_thumb']['size']) != 0) {
                $mp4_thumb = $_FILES['video_thumb'];
                for ($i = 0; $i < count($mp4_thumb['name']); $i++) {
                    $_FILES["video_thumb"]['name'] = $mp4_thumb['name'][$i];
                    $_FILES["video_thumb"]['type'] = $mp4_thumb['type'][$i];
                    $_FILES["video_thumb"]['tmp_name'] = $mp4_thumb['tmp_name'][$i];
                    $_FILES["video_thumb"]['error'] = $mp4_thumb['error'][$i];
                    $_FILES["video_thumb"]['size'] = $mp4_thumb['size'][$i];
                    if ($this->upload->do_upload('video_thumb')) {
                        $disp_image = $this->upload->data();
                        $uploadedImage = $disp_image['file_name'];
                        $video_thumb[] = $uploadedImage;
                    } else {
                        $message = ['code' => "0", 'message' => $this->upload->display_errors("", "")];
                        return $message;
                    }
                }
            }

            $videoIndex = "0";
            /**
             * Condition is used to check whether file is coming or not
             */
            if ($files_list) {
                if ($video_thumb) {
                    foreach ($files_list as $key => $value) {
                        if ($value['media_type'] == "V") {
                            $files_list[$key]['video_thumb'] = $video_thumb[$videoIndex];
                            $videoIndex++;
                        }
                    }
                }
                $message = ['code' => "1", 'message' => "Media uploaded successfully", "data" => $files_list];
                return $message;
            } else {
                $message = ['code' => "0", 'message' => $this->upload->display_errors("", "")];
                return $message;
            }
        }
    }

    function send_notification_ios_customer($payload,$device_tokens)
    {   
        $development = true;
    	$Production = true;
    	
        if($Production)
        {
           
            $keyfile = 'AuthKey_FCUPCPXU6H.p8';               # <- Your AuthKey file
            $keyid = 'FCUPCPXU6H';                            # <- Your Key ID
            $teamid = '66H7Z5943S';                           # <- Your Team ID (see Developer Portal)
            $bundleid = 'com.pointzero.gifticon';                # <- Your Bundle ID
            $url = 'https://api.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
            $token = $device_tokens;   

          
            $arSendData = array();

            $arSendData['aps']['tag']          = $payload['aps']['tag'];
            $arSendData['aps']['alert']['title']         = $payload['aps']['alert']['title'];
            $arSendData['aps']['alert']['body']         = $payload['aps']['alert']['body']; 
            $arSendData['aps']['sound']         = "default";

            $sendDataJson = json_encode($arSendData);
            
            $message =  $sendDataJson; 

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
            
            if ($result === FALSE) {
            throw new Exception("Curl failed: ".curl_error($http2ch));
            }

            $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
           
            return true;
        }
    }

    function send_notification_ios_customer_new($payload,$device_tokens)
    {   
        $development = true;
        $Production = true;
        
        if($Production)
        {
           
            $keyfile = 'AuthKey_FCUPCPXU6H.p8';               # <- Your AuthKey file
            $keyid = 'FCUPCPXU6H';                            # <- Your Key ID
            $teamid = '66H7Z5943S';                           # <- Your Team ID (see Developer Portal)
            $bundleid = 'com.pointzero.gifticon';                # <- Your Bundle ID
            $url = 'https://api.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
            $token = $device_tokens;   

          
            $arSendData = array();

            $arSendData['aps']['tag']          = $payload['aps']['tag'];
            $arSendData['aps']['alert']['title']         = '';
            $arSendData['aps']['alert']['body']         = ''; 
            $arSendData['aps']['sound']         = '';
            $arSendData['aps']['mutable-content']         = 1;
            $arSendData['aps']['content-available']         = 1;
            $arSendData['aps']['purchase_id']         = $payload['aps']['purchase_id'];
            

            $sendDataJson = json_encode($arSendData);
            
            $message =  $sendDataJson; 

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
            
            if ($result === FALSE) {
            throw new Exception("Curl failed: ".curl_error($http2ch));
            }

            $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
           
            return true;
        }
    }
    function send_notification_ios_business($payload,$device_tokens)
    {   
        $development = true;
        $Production = true;
        
      
        if($Production)
        {
           
                $keyfile = 'AuthKey_FCUPCPXU6H.p8';               # <- Your AuthKey file
                $keyid = 'FCUPCPXU6H';                            # <- Your Key ID
                $teamid = '66H7Z5943S';                           # <- Your Team ID (see Developer Portal)
                $bundleid = 'com.pointzero.gifticonbusiness';                # <- Your Bundle ID
                $url = 'https://api.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
                $token = $device_tokens;   

                $arSendData = array();

                $arSendData['aps']['tag']          = $payload['aps']['tag'];
                $arSendData['aps']['alert']['title']         = $payload['aps']['alert']['title'];
                $arSendData['aps']['alert']['body']         = $payload['aps']['alert']['body']; 
                $arSendData['aps']['sound']         = "default";

                $sendDataJson = json_encode($arSendData);
            
                $message =  $sendDataJson; 

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
                
                if ($result === FALSE) {
                throw new Exception("Curl failed: ".curl_error($http2ch));
                }

                $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
                
                return true;
        }
    }

    function push_to_apns($msgpush, $deviceToken){

        $authKey                = "AuthKey_FCUPCPXU6H.p8";
        $arParam['teamId']      = '66H7Z5943S';
        $arParam['authKeyId']   = 'FCUPCPXU6H';
        $arParam['apns-topic']  = 'com.pointzero.gifticon';
        

        $arClaim                = ['iss'=>$arParam['teamId'], 'iat'=>time()];
        $arParam['p_key']       = file_get_contents($authKey);
        $arParam['header_jwt']  = JWT::encode($arClaim, $arParam['p_key'], $arParam['authKeyId'], 'RS256');
    
        $arSendData = array();

        $arSendData['aps']['alert']['tag']          = $msgpush['aps']['tag'];
        $arSendData['aps']['alert']['title']        = $msgpush['aps']['alert']['title'];
        $arSendData['aps']['alert']['body']         = $msgpush['aps']['alert']['body']; 
       // $arSendData['aps']['alert']['booking_id']   = $msgpush['booking_id'];
        $arSendData['aps']['sound']                 = "default";
        
        $sendDataJson = json_encode($arSendData);
        
        $endPoint = 'https://api.push.apple.com/'; // https://api.push.apple.com/3/device

        //　Preparing request header for APNS
        $ar_request_head[] = sprintf("content-type: application/json");
        $ar_request_head[] = sprintf("authorization: bearer %s", $arParam['header_jwt']);
        $ar_request_head[] = sprintf("apns-topic: %s", $arParam['apns-topic']);

        $dev_token = $deviceToken;  // Device token

        $url = sprintf("%s/%s", $endPoint, $dev_token);
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendDataJson);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $ar_request_head);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        print_r($ch);
        if(empty(curl_error($ch))){
           // echo "empty curl error \n";
        }
        curl_close($ch);
        return TRUE;
    }

    function send_fcm_notification($registatoin_ids, $message)
    {
    	$url = 'https://fcm.googleapis.com/fcm/send';

    	$fields = array(
    		'priority' => 'high',
    		'registration_ids' => array($registatoin_ids),
    		'data' => array("message" => $message)
    		);

        $headers = array(
          'Authorization: key=AAAAZq-fD-Q:APA91bH-7xpDpmUXrrLRigWFRG9iLZD_9mx081I_4j4fkGzmE-1KZNKh2q54EKjWUNSBq8EFWCabV5tW5oY1yOmI_4cCUlASjs5JToyMTYun3UbvKvbVWUcG_kFwOYvMSdsNk9u00H7o',
              'Content-Type: application/json'
          );

          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, $url);

          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

          $result = curl_exec($ch);

          if ($result === FALSE) {
              die('Curl failed: ' . curl_error($ch));
          }
          //print_r($result);
          curl_close($ch);
          return;
      }

      function base64($data) {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }




        function create_thumb($sourcePath,$destinationPath,$height,$width){
           $this->load->library("image_lib");
           $config['create_thumb'] = FALSE;
           $config['source_image'] = $sourcePath;
           $config['new_image'] = $destinationPath;
           $config['maintain_ratio'] = FALSE;
           $config['width'] = $width;
           $config['height'] = $height;
           $this->image_lib->initialize($config);
           $this->image_lib->resize();
       }

       function convertToUtcTime($passdate,$zone)
       {
        try{        
            $utc = $passdate;
            $dt = new DateTime($utc);
            $tz = new DateTimeZone($zone); 
            $dt->setTimezone($tz);        
            $today = $dt->format("h:i A");
            return $today; 
        }
        catch(Exception $e){            
            throw new Exception("Wrong timezone format", 1);

        }
    }  

    function convertToUserDateTime($passdate,$zone)
    {
        try{        
            $utc = $passdate;
            $dt = new DateTime($utc);
            $tz = new DateTimeZone($zone); 
            $dt->setTimezone($tz);        
            $today = $dt->format("Y-m-d H:i");
            return $today; 
        }
        catch(Exception $e){            
            throw new Exception("Wrong timezone format", 1);
        }
    }

    function date_convert($date,$fromtimezone,$totimezone,$dateformat) 
    {
        date_default_timezone_set($fromtimezone);

        if($date == '0000-00-00 00:00:00')
            return $date;
        $date = new DateTime($date);

        $date->setTimezone(new DateTimeZone($totimezone));

        return $date->format($dateformat);
    }


    function date_getFullTimeDifference( $start, $end ){
    	$uts['start'] = strtotime( $start );
    	$uts['end'] = strtotime( $end );
    	if( $uts['start']!==-1 && $uts['end']!==-1 ){
    		if( $uts['end'] >= $uts['start'] ){
    			$diff    =    $uts['end'] - $uts['start'];
    			if( $years=intval((floor($diff/31104000))) )
    				$diff = $diff % 31104000;

    			if( $months=intval((floor($diff/2592000))) )
    				$diff = $diff % 2592000;

    			if( $week=intval((floor($diff/604800))))
    				$diff=$diff % 604800;

    			if( $days=intval((floor($diff/86400))) )
    				$diff = $diff % 86400;

    			if( $hours=intval((floor($diff/3600))) )
    				$diff = $diff % 3600;

    			if( $minutes=intval((floor($diff/60))) )
    				$diff = $diff % 60;

    			$diff    =    intval( $diff );
    			return( array('years'=>$years,'months'=>$months,'weeks'=>$week,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
    		}
    		else
    		{
    			echo "Ending date/time is earlier than the start date/time";
    		}
    	}
    	else
    	{
    		echo "Invalid date/time data detected";
    	}
    }

    
    function getTimeString($end)
    {
    	$startDate = date(('Y-m-d H:i'),strtotime($end));
    	$endDate = date("Y-m-d H:i");
    	if($startDate == $endDate)
    	{   
    		$time="Just now";
    	}
    	else
    	{
    		$timer = $this->date_getFullTimeDifference($startDate,$endDate);
    		if($timer['years'] != 0){
    			$time =  $timer['years']." year ago";    
    		}
    		else if($timer['months'] != 0){
    			$time =  $timer['months']." month ago";                            
    		}   
    		else if($timer['weeks'] != 0 ){
    			$time =  $timer['weeks']." weeks ago";
    		}
    		else if($timer['days'] != 0 ){
    			if($timer['days'] <1){
    				$time =  $timer['days']." days ago";
    			}
    			else if($timer['days'] <=1){ 
    				$time =  "Yesterday";                          
    			}
    			else{
    				$time =  $timer['days']." days ago";
    			}
    		}
    		else if($timer['hours'] != 0){
    			$time = round($timer['hours'])." hours ago";
    		}
    		else if($timer['minutes'] != 0){
    			$time = round($timer['minutes'])." min ago";
    		}
    	}
    	return $time;
    }

    /* This function is used to check incoming String is json or not only for dipali*/
    function isJSON($string)
    {
    	return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /*
     *  function is used to generate OTP
     */
    function generateOtp($length="")
    {
    	return rand(1000,9999);
    }

     /*
     *  This function is used to send sms from the twilio
     */

     function sendSMS($phone, $message)
     {
        $id = "AC13f4b8d41368625aababf5b9f787e753"; 
        $token = "726f317715af40f81b206acc0ec04994";
        
        $phone = $phone; //'+971553753752';
        $url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages.json";
        $data = array (
            'From' => "+12702296876", 
            'To' => $phone,
            'Body' => $message,
            );
        $post = http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$id:$token");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($result, true);
        if($httpcode == 201)
            return true;
        else
            return false;
    }

    public function media_upload_S3($field, $path, $isBase64 = "0")
    {
        if ($isBase64 == "0") {
            $tmp = explode('.', $_FILES[$field]['name']);
            $ext = end($tmp);
            if (in_array($ext, $this->valid_formats)) {
                $image_name = uniqid() . strtotime(date("Ymd his")) . "." . $ext;
                try
                {
                   $this->s3->putObject([
                        'Bucket' => $this->s3val['s3']['bucket'],
                        'Key' => $path . $image_name,
                        'SourceFile' => $_FILES[$field]['tmp_name'],
                        'ServerSideEncryption' => 'AES256',
                        'ACL' => 'public-read',
                        ]);

                    return $image_name;

                } catch (S3Exception $e) {
                    print_r($e);
                    die;
                    return false;
                }
             } else {
                 return false;
             }
        } else {
            try
            {
                /* code to upload file on bucket */
                $this->s3->putObject([
                    'Bucket' => $this->s3val['s3']['bucket'],
                    'Key' => $path . basename($field),
                    'SourceFile' => $field,
                    'ServerSideEncryption' => 'AES256',
                    'ACL' => 'public-read',
                    ]);
                    echo "<pre>";
                    print_r($this->s3->putObject);
                    die;
                return basename($field);
            } catch (S3Exception $e) {
               /*print_r($e);
                    die;*/
                return false;
            }
        }
    }

    public function media_upload_S3_multiple($field,$i, $path, $isBase64 = "0")
    {
        if ($isBase64 == "0") {
            $tmp = explode('.', $_FILES[$field]['name'][$i]);
            $ext = end($tmp);
            if (in_array($ext, $this->valid_formats)) {
                $image_name = uniqid() . strtotime(date("Ymd his")) . "." . $ext;
                try
                {
                    /* code to upload file on bucket */
                    $this->s3->putObject([
                        'Bucket' => $this->s3val['s3']['bucket'],
                        'Key' => $path . $image_name,
                        'SourceFile' => $_FILES[$field]['tmp_name'][$i],
                        'ServerSideEncryption' => 'AES256',
                        'ACL' => 'public-read',
                        ]);
                    return $image_name;

                } catch (S3Exception $e) {
                    return false;
                }
             } else {
                 return false;
             }
        } else {
            try
            {
                /* code to upload file on bucket */
                $this->s3->putObject([
                    'Bucket' => $this->s3val['s3']['bucket'],
                    'Key' => $path . basename($field),
                    'SourceFile' => $field,
                    'ServerSideEncryption' => 'AES256',
                    'ACL' => 'public-read',
                    ]);
                return basename($field);
            } catch (S3Exception $e) {
                return false;
            }
        }
    }


   

    /**
    * @param $field is filed to uplod and path is where to upload
    * This function is used to upload excel files
    */
    public function excel_file_upload($field,$path)
    {
        $config['upload_path']   = $path;        
        $config['allowed_types'] = 'xls|xlsx|csv';         
        /*print_r($_FILES[$field]); die;*/
        $file_name=$_FILES[$field]['name'];                         
        $explode=explode('.',$file_name);
        $file_ext=strtolower(end($explode));
        $fname=$config['file_name']=time().".".$file_ext;

        
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload($field))
        {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        }
        else
        {
            $data =$this->upload->data();
            $fn=$data['file_name'];
            return $fn;                   
        }
    }

    public function image_upload($field,$path) {
        $config['upload_path'] = $path;
        $config['allowed_types'] = '*';
        $filename = $config['file_name'] = strtotime(date("Ymd his"));
        $config['max_size'] = '*';
        $config['max_width']  = '*';
        $config['max_height']  = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if($this->upload->do_upload($field)) { 
            $w = $this->upload->data();
            $image_name = $w['file_name'];
            return $image_name;
        } else {
            return false;
        }
    }

  
}
?>