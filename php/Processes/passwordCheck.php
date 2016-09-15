<?php
require_once('..\utilities\functions.php');
include('..\utilities\mysqli_connect.php');
header('Access-Control-Allow-Origin: *');

$return  = array();

// Get User ID.  Either passed as the ID or pass as email
//If email, then get ID
$id       = get_variable('idPerson', $_POST);
$email    = get_variable('primEmail', $_POST);
$password = get_variable('password', $_POST);

//If no password provided, then exit
if (!isset($password)) {
//  echo "No Password";
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "No Password", $return);
  echo json_encode($return);  
  exit;
}


//If ID is not set and e-mail is set then get ID
if (!isset($id) and isset($email)) {
  $params = add_where('primEmail', $email, $params = array());
  $response = select_from_table('users', 'idPerson', $params);
  $response = json_decode($response, true);
  if (!empty($response)) {
    $id = $response[0]['idPerson'];
  //  echo 'ID:'.$id; 
  }
}

//If ID is not set, then exit with message
if (!isset($id)) {
//  echo "E-Mail $email does not exist";
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Email $email does not exist", $return);
  echo json_encode($return);
  exit;
}
else {
//  echo 'user exists'.json_encode($response);
  $return = add_message("message", "User Exists", $return); 
}

//Get current password
$params = add_where('idPerson', $id, $params = array());
$response = select_from_table('password', 'password', $params);
$response = json_decode($response, true);
if (!empty($response)) {
    $oldPassword = $response[0]['password'];
//    echo 'Old Password:'.$oldPassword;
}
else {
 // echo "Password was never set";
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Password was never set", $return);
  echo json_encode($return);  
  exit;
}
//Validate the password
//echo 'New Password:'.$password;
$valid = validatePassword($oldPassword, $password, $id);

if ($valid == false) {  //failed validation
  $return = add_message("returnCode", "2", $return);
  $return = add_message("message", "Failed Validation", $return);
}
else {
  $return = add_message("returnCode", "1", $return);
  $return = add_message("message", "Passed Validation", $return);
}  
echo json_encode($return); 
?>