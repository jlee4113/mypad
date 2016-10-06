<?php
  require_once('../utilities/functions.php');
  include('../utilities/globals.php');
  header('Access-Control-Allow-Origin: *');


//Declarations
$return = new json();
$response = array();

//Declarations
$return = new json();


// Get Parameters
$email     = get_variable('primEmail', $_POST);

//Make sure primary e-mail is set
if (empty($email)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Email Empty",$return->messages);
  echo json_encode($return);
  exit;
}



//Check if exists

$table = 'users';
$where = add_where("primEmail", $email, $where = array());
$fields = "*";
$response = select_from_table($table, $fields, $where);

// Then add the user to the person table with only the e-mail address
if (empty($response)) {
  $return->returnCode = '1';
  $return->messages = add_to_array("message","Email does not exist",$return->messages);
  $return->data = add_to_array("email",$email,$return->data);
} else {
  $return->returnCode = '2';
  $return->messages = add_to_array("message","Email exists",$return->messages);
  $return->data = $response;
}
echo json_encode($return);

?>