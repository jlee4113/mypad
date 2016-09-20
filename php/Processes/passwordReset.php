<?php
require_once('../utilities/functions.php');
include('../utilities/mysqli_connect.php');
header('Access-Control-Allow-Origin: *');
// Get User ID.  Either passed as the ID or pass as email
//If email, then get ID

class json {
    public $returnCode = "";
    public $messages = array();
    public $data = array();
}

$id       = get_variable('idPerson', $_POST);
$email    = get_variable('primEmail', $_POST);
$password = get_variable('password', $_POST);
$return = new json();

//If ID is not set and e-mail is set then get ID
if (!isset($id) and isset($email)) {
  $params = add_where('primEmail', $email, $params = array());
  $response = select_from_table('users', 'idPerson', $params);
  //echo $response;
  $response = json_decode($response, true);
  if (!empty($response)) {
    $id = $response[0]['idPerson'];
  }
}

//If ID is not set, then exit with message
if (!isset($id)) {
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Cannot find user.", $return);
  echo json_encode($return);
  exit;
}

//Create a random password
$password = generateRandomString('5');

//Hash the password provided
$hash = encryptPassword($password);

//Save new password for user
//If already exists, then update password and if not insert record
$params = array();
$response = null;
$params = add_where('idPerson', $id, $params);
$response = select_from_table('password', 'idPerson', $params);
if (empty(json_decode($response, true))) {
  //Insert
  $record = array();
  $records = array();
  $record = add_field('idPerson', $id, $record);
  $record = add_field('password', $hash, $record);
  $record = add_field('misses', "0", $record);
  $record = add_field('locked', "0", $record);
  array_push($records, $record);
  insert_into_table('password', $records);
  echo 'Password Created - New Password is '.$password;
} else {
  //Modify
  $update  = array();
  $where   = array();
  $data    = array();
  $update  = add_field("password", $hash, $update);
  $update  = add_field("misses", "0", $update);
  $update  = add_field("locked", "0", $update);
  $where   = add_where("idPerson", $id, $where);
  modify_record('password', $update, $where);
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Successfuly Updated",$return->messages);
  $return->data = add_to_array("newPassword",$password,$return->data);
  echo json_encode($return);
}
?>