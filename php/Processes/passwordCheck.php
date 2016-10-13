<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');

//Declarations
$return = new json();

//get Parameters
$id       = get_variable('idPerson', $_GET);
$email    = get_variable('primEmail', $_GET);
$password = get_variable('password', $_GET);

//If no password provided, then exit
if (!isset($password)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Password",$return->messages);
  echo json_encode($return);  
  exit;
}


//If ID is not set and e-mail is set then get ID
if (!isset($id) and isset($email)) {
  $params = add_where('primEmail', $email, $params = array());
  $response = select_from_table('users', 'idPerson', $params);
  if (!empty($response)) {
    $id = $response[0]['idPerson'];
  //  echo 'ID:'.$id; 
  }
}

//If ID is not set, then exit with message
if (!isset($id)) {
  //  echo "E-Mail $email does not exist";
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Email $email does not exist",$return->messages);  
  echo json_encode($return);
  exit;
} else {
  //  echo 'user exists'.json_encode($response);
  $return->messages = add_to_array("message","User Exists",$return->messages);  
}

//Get current password
$params = add_where('idPerson', $id, $params = array());
$response = select_from_table('password', 'password', $params);
if (!empty($response)) {
  $oldPassword = $response[0]['password'];
  //    echo 'Old Password:'.$oldPassword;
} else {
 // echo "Password was never set";
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Password was never set",$return->messages);   
  echo json_encode($return);  
  exit;
}
//Validate the password
//echo 'New Password:'.$password;
$valid = validatePassword($oldPassword, $password, $id);

if ($valid == false) {  //failed validation
  $return->returnCode = '2';
  $return->messages = add_to_array("message","Failed Validation",$return->messages);   
}
else {
  $return->returnCode = '1';
  $return->messages = add_to_array("message","Passed Validation",$return->messages);    
}  
echo json_encode($return); 
?>