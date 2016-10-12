<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');

//Declarations
$return   = new json();
//these are the input parameters needed
//$email = 'david.smith@me.com';
//$password = 'mypassword';
// echo "Start Process \n";
$email     = get_variable('primEmail', $_POST);
$namefirst = get_variable('namefirst', $_POST);
$namelast  = get_variable('namelast', $_POST);
$password  = get_variable('password', $_POST);

//Make sure primary e-mail is set
if (empty($email)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Email is empty",$return->messages);
  echo json_encode($return);
  exit; 
}

//Make sure the password was set
if (empty($password)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Password is empty",$return->messages);
  echo json_encode($return);
  exit; 
}
//First, check if user already exists
$table = 'users';
$where = add_where("primEmail", $email, $where = array());
$fields = "primEmail";
$response = select_from_table($table, $fields, $where);
// Then add the user to the person table with only the e-mail address
if (empty($response)) {
//  echo "User not Found \n";
  echo "empty";
  $record  = array();
  $records = array();
  $record  = add_field("primEmail", $email, $record);
  array_push($records, $record);
  //echo "Start Insert Table.".json_encode($table). "\n";
  //echo "Start Insert Fields.".json_encode($records). "\n";

  $id = insert_into_table($table, $records);
  $return->data = add_to_array("idperson",$id,$return->data);

//If ID is not set, then exit with message
  if (!isset($id)) {
      $return->returnCode = '8';
      $return->messages = add_to_array("message","Email $email was not saved.  Contact System Administrator",$return->messages);
      echo json_encode($return);
      exit;
  }  
  // Now add the password to the password table with unique ID assigned
  // Hash the password provided
  $hash = encryptPassword($password);
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
    $id = insert_into_table('password', $records);
    $return->returnCode = '3';
        $return->messages = add_to_array("message","User created and password set",$return->messages);
  } else {
    //Modify
    $update  = array();
    $where   = array();
    $update  = add_field("password", $hash, $update);
    $update  = add_field("misses", "0", $update);
    $update  = add_field("locked", "0", $update);
    $where   = add_where("idPerson", $id, $where);
    modify_record('password', $update, $where);
    $return->returnCode = '3';
    $return->messages = add_to_array("message","User created and password set",$return->messages);
  }
}
else {    //only update password
  $return->returnCode = '2';
  $return->messages = add_to_array("Email already exists. Cannot create.",$return->messages);
}
?>