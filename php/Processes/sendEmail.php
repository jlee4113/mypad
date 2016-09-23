<?php
require_once('../utilities/functions.php');
require_once('../utilities/creds.php');
define('REQUIRED_FILE','../Vendor/aws/aws-autoloader.php');

$email     = get_variable('sendTo', $_POST);
$content   = get_variable('emailContent',$_POST);
$title     = get_variable('emailTitle',$_POST);

define('SENDER', 'ninjas@mypadaz.com');
define('RECIPIENT', $email);
define('REGION','us-west-2');
define('SUBJECT','Password Update');
define('BODY',$content);

require REQUIRED_FILE;

use Aws\Ses\SesClient;

$client = SesClient::factory(array(
    'version'=> 'latest',
    'region' => REGION
));

$request = array();
$request['Source'] = SENDER;
$request['Destination']['ToAddresses'] = array(RECIPIENT);
$request['Message']['Subject']['Data'] = SUBJECT;
$request['Message']['Body']['Text']['Data'] = BODY;

try {
     $result = $client->sendEmail($request);
     $messageId = $result->get('MessageId');
     echo("Email sent! Message ID: $messageId"."\n");

} catch (Exception $e) {
     echo("The email was not sent. Error message: ");
     echo($e->getMessage()."\n");
}

?>