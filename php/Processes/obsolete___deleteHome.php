<?php
require_once('../utilities/functions.php');
nclude('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');
//Declarations
$return = new json();
$where   = array();
$record  = array();
$records = array();

//these are the input parameters needed
$idHome    = get_variable('idHome', $_POST);

//Make sure the Persons ID is set
if (empty($idHome)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Home is Empty",$return->messages);
  echo json_encode($return);
  exit; 
}

//Check if the Home ID is valid
$table = 'homes';
$where = add_where("idHome", $idHome, $where);
$fields = "idHome";
$response = select_from_table($table, $fields, $where);
$EmptyTestArray = array_filter($response);
if (empty($EmptyTestArray)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Invalid Home ID $idHome",$return->messages);	
  echo json_encode($return);
  exit;
}

//Delete Record
delete_from_table($table, $where);

//Set success return code
$return->returnCode = '0';
$return->messages = add_to_array("message","Home successfully deleted",$return->messages);	
echo json_encode($return);
?>