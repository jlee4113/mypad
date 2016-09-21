<?php
require_once('../utilities/functions.php');
require_once('../utilities/creds.php');

$email     = get_variable('sendTo', $_POST);
$password = get_variable('password', $_POST);

// Replace path_to_sdk_inclusion with the path to the SDK as described in
// http://docs.aws.amazon.com/aws-sdk-php/v2/guide/quick-start.html
define('REQUIRED_FILE','../Vendor/aws/aws-autoloader.php');

// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
define('SENDER', 'ninjas@mypadaz.com');

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
define('RECIPIENT', $email);

// Replace us-west-2 with the AWS region you're using for Amazon SES.
define('REGION','us-west-2');

define('SUBJECT','Password Update');
define('BODY','Your new password is '.$password);

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