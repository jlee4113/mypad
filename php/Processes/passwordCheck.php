<?php
require_once('..\utilities\functions.php');
include('..\utilities\mysqli_connect.php');
header('Access-Control-Allow-Origin: *');

// Get User ID.  Either passed as the ID or pass as email
//If email, then get ID
$id       = get_variable('idPerson', $_POST);
$email    = get_variable('primEmail', $_POST);
$password = get_variable('password', $_POST);

//If no password provided, then exit
if (!isset($password)) {
  echo "No Password";
  return("No Password provided for $email try again");
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
  echo "E-Mail $email does not exist";
}
else {
  echo 'user exists'.json_encode($response);
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
  echo "Password was never set";
}
//Validate the password
//echo 'New Password:'.$password;
$valid = validatePassword($oldPassword, $password, $id);
//echo 'The validation is: '.$valid;
echo json_encode($valid);
//dummy comment

?>