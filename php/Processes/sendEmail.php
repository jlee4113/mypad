<?php
require_once('../utilities/functions.php');
require_once('../utilities/creds.php');
define('REQUIRED_FILE','../Vendor/aws/aws-autoloader.php');
$email     = get_variable('sendTo', $_POST);
$content   = get_variable('emailContent',$_POST);
$title     = get_variable('emailTitle',$_POST);

//Validations
if(empty($email)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Email address is Empty",$return->messages);  
  echo json_encode($return);
  exit; 
}
if(empty($title)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Title of Email is Empty",$return->messages);  
  echo json_encode($return);
  exit; 
}

define('SENDER', 'ninjas@mypadaz.com');
define('RECIPIENT', $email);
define('REGION','us-west-2');

define('SUBJECT','Password Update');
define('BODY',$content);
require REQUIRED_FILE;

use Aws\Ses\SesClient;

$client = SesClient::factory(array(
    'version'=> 'latest',
    'region' => REGION,
));

$request = array();
$request['Source'] = SENDER;
$request['Destination']['ToAddresses'] = array(RECIPIENT);
$request['Message']['Subject']['Data'] = SUBJECT;
$request['Message']['Body']['Text']['Data'] = BODY;

try {
     $result = $client->sendEmail($request);
     $messageId = $result->get('MessageId');
     $return->returnCode = '0';
     $return->messages = add_to_array("message","Email sent! Message ID: $messageId",$return->messages);  

} catch (Exception $e) {
     $return->returnCode = '8';
     $return->messages = add_to_array("message","The email was not sent. Error message: ",$return->messages); 
     $message = $e->getMessage();
     $return->messages = add_to_array("message",$message,$return->messages); 
}
?>