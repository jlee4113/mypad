<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');
//Declarations
$return = new json();
$where   = array();
$record  = array();
$records = array();
$update  = array();

//these are the input parameters needed
$idOffer       = get_variable('idListing', $_POST);
$amount        = get_variable('amount', $_POST);
$earnest       = get_variable('earnest', $_POST);
$expires       = get_variable('expires', $_POST);
$targetDate    = get_variable('idBuyer', $_POST);
$contingencies = get_variable('contingencies', $_POST);
$cash          = get_variable('cash', $_POST);
$downPayment   = get_variable('downPayment', $_POST);
$prequal       = get_variable('prequal', $_POST);
$status        = get_variable('status', $_POST);

//Make sure one of the 3 variables are populated
if (empty($idOffer)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Offer ID",$return->messages); 
  echo json_encode($return);
  exit;    
}
//Check if Listing ID exists
$table = 'offers';
if (!$return->returnCode == '8') {
//Make sure idListing is valid	
  unset($where);
  $where = add_where("idOffer", $idOffer, $where);
  $fields = "idOffer";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '8';
    $return->messages = add_to_array("message","Invalid idOffer $idOffer",$return->messages); 
  }

if ($return->returnCode == '8') {
  echo json_encode($return);
  exit; 
}


$where = add_where("idOffer", $idOffer, $where);
if (isNotEmpty($amount)) {
  $update  = add_field("amount", $amount, $update); 
}
if (isNotEmpty($earnest)) {
  $update  = add_field("earnest", $earnest, $update); 
}
if (isNotEmpty($expires)) {
  $update  = add_field("expires", $expires, $update); 
}
if (isNotEmpty($targetDate)) {
  $update  = add_field("targetDate", $targetDate, $update); 
}
if (isNotEmpty($contingencies)) {
  $update  = add_field("contingencies", $contingencies, $update); 
}
if (isNotEmpty($cash)) {
  $update  = add_field("cash", $cash, $update); 
}
if (isNotEmpty($downPayment)) {
  $update  = add_field("downPayment", $downPayment, $update); 
}
if (isNotEmpty($prequal)) {
  $update  = add_field("prequal", $prequal, $update); 
}
if (isNotEmpty($status)) {
  $update  = add_field("status", $status, $update); 
}

modify_record($table, $update, $where);
$return->returnCode = '0';
$return->messages = add_to_array("message","Successfuly Updated",$return->messages);
  
echo json_encode($return);
?>