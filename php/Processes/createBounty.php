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
$idListing = get_variable('idListing', $_POST);

//Make sure the Persons ID is set
if (empty($idPerson)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Person is Empty",$return->messages);    
}

//Make sure the idListing is set
if (empty($idListing)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Address is not set",$return->messages);  
}

if (!$return->returnCode == '8') {
//Make sure idPerson is valid	
  $table = 'users';
  unset($where);
  $where = add_where("idPerson", $idPerson, $where);
  $fields = "idPerson";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '8';
    $return->messages = add_to_array("message","Invalid idPerson $idPerson",$return->messages); 
  }
//Make sure idListing is valid
  $table = 'listings';
  unset($where);
  $where = add_where("idListing", $idListing, $where);
  $fields = "idListing";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '8';
    $return->messages = add_to_array("message","Invalid idListing $idListing",$return->messages); 
  }
// Make sure there is not already a bounty for the person/listing
  $table = 'bounty';
  unset($where);
  $where = add_where("idPerson", $idPerson, $where);
  $where = add_where("idListing", $idListing, $where);
  $fields = "idBounty";
  $response = select_from_table($table, $fields, $where);
  if (!empty($response)) {
    $return->returnCode = '8';
    $return->messages = add_to_array("message","Bounty already exists for $idPerson $idListing",$return->messages); 
  }
}

if ($return->returnCode == '8') {
  echo json_encode($return);
  exit; 
}

//Add record

$table = 'bounty';

// Parameters to update
$record  = add_field("idPerson", $idPerson, $record);
$record  = add_field("idListing", $idListing, $record);
array_push($records, $record);
//Home ID to update
insert_into_table($table, $records);

//Get the Bounty ID and send back
unset($where);
$where = add_where("idPerson", $idPerson, $where);
$where = add_where("idListing", $idListing, $where);
$fields = "idBounty";
$response = select_from_table($table, $fields, $where);
$return->data = add_to_array("idBounty",$response,$return->data);

//Set success return code
$return = add_message("returnCode", "0", $return);
$return = add_message("message", "Home successfully created", $return);

echo json_encode($return);
?>