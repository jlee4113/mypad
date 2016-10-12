<?php
//This is the return from an e-mail sent to the user to verify that the e-mail is correct
//it sets the variable users-verified to be "X"
  require_once('../utilities/functions.php');
  include('../utilities/globals.php');
  header('Access-Control-Allow-Origin: *');


//Declarations
$return = new json();
$response = array();
$record   = array();
$records  = array();
$param    = array();
$params   = array();

// Get Parameters
$id     = get_variable('idPerson', $_POST);

//Make sure primary e-mail is set
if (empty($id)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID is empty",$return->messages);
  echo json_encode($return);
  exit;
}

//Check if exists
$table = 'users';
$where = add_where("idPerson", $id, $where = array());
$fields = "*";
$response = select_from_table($table, $fields, $where);

// Then add the user to the person table with only the e-mail address
if (empty($response)) {
  $return->returnCode = '1';
  $return->messages = add_to_array("message","Person does not exist",$return->messages);
  $return->data = add_to_array("idPerson",$id,$return->data);
} else {
  $return->returnCode = '2';
  $return->messages = add_to_array("message","Email Validated",$return->messages);
  $return->data = $response;
  $param  = add_field("idPerson", $id, $param);
  $record = add_field('verified', 'X', $record);
  array_push($records, $record);
  array_push($params, $param);
  modify_record($table, $records, $params);
}
echo json_encode($return);
?>