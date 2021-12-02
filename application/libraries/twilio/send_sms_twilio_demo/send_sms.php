<?php
require('twilio-php-master/Services/Twilio.php');

$account_sid = "ACeb3868827ba28465b836214b7bc24550"; // Your Twilio account sid
$auth_token = "afc00ef01e69ee73b3551b6b46afa5a7"; // Your Twilio auth token

$client = new Services_Twilio($account_sid, $auth_token);
$message = $client->account->messages->sendMessage(
  '+1 845-406-9566', // From a Twilio number in your account
  '+917383422165', // Text any number
  "Hello how are you !"
);

print $message->sid;
?>