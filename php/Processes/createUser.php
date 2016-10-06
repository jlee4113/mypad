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
  insert_into_table($table, $records);
//  echo "started";
  $last_id = mysql_insert_id();
  echo "Last ID:".$last_id;
  exit;
}
?>