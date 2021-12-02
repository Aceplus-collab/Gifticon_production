<?php
require('twilio-php-master/Services/Twilio.php');

$account_sid = "AC33ee3dd19491cd93eddf204c7795b091"; // Your Twilio account sid
$auth_token = "6ba9e4de638542be83ba07106746a060"; // Your Twilio auth token

$client = new Services_Twilio($account_sid, $auth_token);
$message = $client->account->messages->sendMessage(
  '+17064053927', // From a Twilio number in your account
  '+919898875710', // Text any number
  "Hello how are you !"
);

print $message->sid;
?>