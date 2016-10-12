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
$idListing     = get_variable('idListing', $_POST);
$idBuyer       = get_variable('idBuyer', $_POST);
$amount        = get_variable('amount', $_POST);
$earnest       = get_variable('earnest', $_POST);
$expires       = get_variable('expires', $_POST);
$targetDate    = get_variable('idBuyer', $_POST);
$contingencies = get_variable('contingencies', $_POST);
$cash          = get_variable('cash', $_POST);
$downPayment   = get_variable('downPayment', $_POST);
$prequal       = get_variable('prequal', $_POST);
$status        = get_variable('status', $_POST);



$return->returnCode = '0';
//Make sure the Persons ID is set
if (empty($idBuyer)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Buyer is Empty",$return->messages);    
}

//Make sure the idListing is set
if (empty($idListing)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Listing is Empty",$return->messages);  
}

//Make sure the idListing is set
if ($amount << 1)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Amount is too low $amount",$return->messages);  
}


if (!$return->returnCode == '8') {
//Make sure idBuyer is valid	
  $table = 'users';
  unset($where);
  $where = add_where("idPerson", $idBuyer, $where);
  $fields = "idPerson";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '2';
    $return->messages = add_to_array("message","Invalid idBuyer $idBuyer",$return->messages); 
  }
//Make sure idListing is valid
  $table = 'listings';
  unset($where);
  $where = add_where("idListing", $idListing, $where);
  $fields = "idListing";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '3';
    $return->messages = add_to_array("message","Invalid idListing $idListing",$return->messages); 
  }
// Make sure there is not already an Offer for the buyer/listing
  $table = 'offers';
  unset($where);
  $where = add_where("idbuyer", $idBuyer, $where);
  $where = add_where("idListing", $idListing, $where);
  $fields = "idOffer";
  $response = select_from_table($table, $fields, $where);
  if (!empty($response)) {
    $return->returnCode = '4';
    $return->messages = add_to_array("message","Offer already exists for $idPerson $idListing",$return->messages); 
  }
}

if (!$return->returnCode == '0') {
  echo json_encode($return);
  exit; 
}

// Convert Boolian
$contingencies = from_boolian($contingencies);
$cash          = from_boolian($cash);
$prequal       = from_boolian($prequal);

//Add record
$table = 'offers';

// Parameters to update
$record  = add_field("idListing", $idListing, $record);
$record  = add_field("idBuyer", $idBuyer, $record);
$record  = add_field("amount ", $amount , $record);
$record  = add_field("earnest", $earnest, $record);
$record  = add_field("expires", $expires, $record);
$record  = add_field("targetDate", $targetDate, $record);
$record  = add_field("contingencies", $contingencies, $record);
$record  = add_field("cash", $cash, $record);
$record  = add_field("downPayment", $downPayment, $record);
$record  = add_field("prequal", $prequal, $record);
$record  = add_field("status", $status, $record);
array_push($records, $record);
//Home ID to update
$response = insert_into_table($table, $records);
$return->data = add_to_array("idOffer",$response,$return->data);

//Set success return code
$return = add_message("returnCode", "0", $return);
$return = add_message("message", "Offer successfully created", $return);

echo json_encode($return);
?>