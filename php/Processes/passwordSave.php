<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');

//Declarations
$return = new json();

//get Parameters
$id       = get_variable('idPerson', $_POST);
$email    = get_variable('email', $_POST);
$password = get_variable('password', $_POST);

//If no password provided, then exit
if (!isset($password)) {
  echo "No Password";
  return("No Password provided for $email try again");
}


//If ID is not set and e-mail is set then get ID
if (!isset($id) and isset($email)) {
  //echo $email;
  $params = add_where('primEmail', $email, $params = array());
  $response = select_from_table('users', 'idPerson', $params);
  if (!empty($response)) {
    $id = $response[0]['idPerson'];
  }
}

//If ID is not set, then exit with message
if (!isset($id)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","E-Mail $email does not exist",$return->messages);
  echo json_encode($return);
  exit;
}
//echo $id;
//Hash the password provided
$hash = encryptPassword($password);

//Save new password for user
//If already exists, then update password and if not insert record
$params = array();
$response = null;
$params = add_where('idPerson', $id, $params);
$response = select_from_table('password', 'idPerson', $params);
//echo $response;
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
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Password Inserted",$return->messages);  
}
else {
  //Modify
  $update  = array();
  $where   = array();
  $update  = add_field("password", $hash, $update);
  $update  = add_field("misses", "0", $update);
  $update  = add_field("locked", "0", $update);
  $where   = add_where("idPerson", $id, $where);
  modify_record('password', $update, $where);
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Password Updated",$return->messages);   
}
echo json_encode($return);
?>