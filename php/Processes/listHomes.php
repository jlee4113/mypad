<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');
//Declarations
$return = new json();
$record  = array();
$records = array();

//these are the input parameters needed
$idHome    = get_variable('idHome', $_GET);
$idPerson  = get_variable('idPerson', $_GET);
$address   = get_variable('address', $_GET;
$zip       = get_variable('zip', $_GET);
$and       = " and ";
if (!empty($idHome)) {
  $where = "homes.idHome = $idHome";
}
if (!empty($idPerson)) {
  if (empty($where)) {
    $where = "homes.idPerson = $idPerson";
  }
  else {
    $where = "$and homes.idPerson = $idPerson";
  }
}
if (!empty($address)) {
  if (empty($where)) {
    $where = "homes.address = $address";
  }
  else {
    $where = "$and homes.address = $address";
  }
}
if (!empty($zip)) {
  if (empty($where)) {
    $where = "homes.zip = $zip";
  }
  else {
    $where = "$and homes.zip = $zip";
  }
}

//Make sure there is a selection criteria given
if (empty($where)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Selection given",$return->messages);  
  echo json_encode($return);
  exit; 
}
//Select data
$table = 'homes';
$query = "SELECT * FROM mypad.homes, mypad.users WHERE $where and mypad.homes.idPerson = mypad.users.idPerson;";
$return->data = general_query($query);
if (empty($return->data)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Homes selected",$return->messages);  
}
else {
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Homes Selected",$return->messages);  
}
echo json_encode($return);
?>