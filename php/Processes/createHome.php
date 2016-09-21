<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');
//Declarations
$return = new json();
$where   = array();
$record  = array();
$records = array();

//these are the input parameters needed
$idPerson  = get_variable('idPerson', $_POST);
$address   = get_variable('address', $_POST);
$address1  = get_variable('address1', $_POST);
$zip       = get_variable('zip', $_POST);
$pic       = get_variable('pic', $_POST);

//Make sure the Persons ID is set
if (empty($idPerson)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Person is Empty",$return->messages);    
}

//Make sure the address is set
if (empty($address)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Address is not set",$return->messages);  
}

//Make sure the Zip Code is set
if (empty($zip)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Zip Code is not set",$return->messages);  
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Zip Code is not set", $return);
}
if ($return->returnCode == '8') {
  echo json_encode($return);
  exit; 
}
$table = 'homes';
//Make sure there is not already an address that matches
unset($where);
$where = add_where("address", $address, $where);
$where = add_where("address1", $address1, $where);
$where = add_where("zip", $zip, $where);
$fields = "idHome";
$response = select_from_table($table, $fields, $where);
$EmptyTestArray = array_filter($response);
if (empty($EmptyTestArray)) {
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Address already exists", $return);
  echo json_encode($return);
  exit;
}
//Check if the Home currently exists
unset($where);
unset($fields);
unset($response);
unset($emptyTestArray);
$where = add_where("idHome", $idHome, $where);
$fields = "idHome";
$response = select_from_table($table, $fields, $where);
$EmptyTestArray = array_filter($response);
if (empty($EmptyTestArray)) {
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Invalid Home ID $idHome", $return);
  echo json_encode($return);
  exit;
}

// Parameters to update
$record  = add_field("address", $address, $record);
$record  = add_field("address1", $address1, $record);
$record  = add_field("zip", $zip, $record);
$record  = add_field("idPerson", $idPerson, $record);
$record  = add_field("pic", $pic, $record);
array_push($records, $record);
//Home ID to update
insert_into_table($table, $records);

//Get the Home ID and send back
unset($where);
$where = add_where("address", $address, $where);
$where = add_where("address1", $address1, $where);
$where = add_where("zip", $zip, $where);
$fields = "idHome";
$response = select_from_table($table, $fields, $where);
$return->data = add_to_array("idHome",$response,$return->data);

//Set success return code
$return = add_message("returnCode", "0", $return);
$return = add_message("message", "Home successfully created", $return);

echo json_encode($return);
?>