<?php
/*echo phpinfo();*/
// Get the PHP helper library from twilio.com/docs/php/install
require('twilio-php-master/Services/Twilio.php'); // Loads the library

// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC9e2a211e863050ffc5e89ac339d53e3b"; 
$token = "e112cf54f78d49525d290da2aa7af3ab"; 
$http = new Services_Twilio_TinyHttp(
    'https://api.twilio.com',
    array('curlopts' => array(
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ))
);

$client = new Services_Twilio($sid, $token, "2010-04-01", $http);
/*$client = new Services_Twilio($sid, $token);*/
$message = $client->account->messages->sendMessage(
  '+12513331592', // From a Twilio number in your account
  '+918000036981', // Text any number
  "Hello how are you !"
);

echo $message->sid;
?>