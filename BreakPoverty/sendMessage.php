<?php
// Install the library via PEAR or download the .zip file to your project folder.
// This line loads the library
require '../Twilio-Server/Services/Twilio.php';

$sid = "ACd4dbf05b911868c0d3b7517bc5b2aee5"; // Your Account SID from www.twilio.com/user/account
$token = "1f59fcb52b10490032e3587917f39b2a"; // Your Auth Token from www.twilio.com/user/account

$client = new Services_Twilio($sid, $token);
$message = $client->account->messages->sendMessage(
  '16479553883', // From a valid Twilio number
  '12899239409', // Text this number
  "Hello RECEIVER!"
);

echo '<h2> Sending Messages with Twilio </h2>';
print $message->sid;

?>
