<?php
require_once('../utilities/functions.php');
define('REQUIRED_FILE','../Vendor/aws/aws-autoloader.php');

$email     = get_variable('sendTo', $_POST);
$password = get_variable('password', $_POST);

// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
define('SENDER', 'ninjas@mypadaz.com');

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
define('RECIPIENT', $email);

// Replace us-west-2 with the AWS region you're using for Amazon SES.
define('REGION','us-west-2');
//define('AWS_KEY','AKIAJE5C433ILFOR56BA');
//define('AWS_SECRET','SQJrx8GtGHGNat2i0iwepXTMGDUPBfLtd80owPTY');

define('SUBJECT','Password Update');
define('BODY','Your new password is '.$password);

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
     echo("Email sent! Message ID: $messageId"."\n");

} catch (Exception $e) {
     echo("The email was not sent. Error message: ");
     echo($e->getMessage()."\n");
}

?>