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
$idListing     = get_variable('idListing', $_POST);
$status        = get_variable('status', $_POST);
$address       = get_variable('address', $_POST);
$zip           = get_variable('zip', $_POST);
$bedrooms      = get_variable('bedrooms', $_POST);
$bathrooms     = get_variable('bathrooms', $_POST);
$totalRooms    = get_variable('totalRooms', $_POST);
$finishedSqFt  = get_variable('finishedSqFt', $_POST);
$lotSizeSqFt   = get_variable('lotSizeSqFt', $_POST);
$askingPrice   = get_variable('askingPrice', $_POST);
$minimum       = get_variable('minimum', $_POST);
$mls           = get_variable('mls', $_POST);
$buyersAgent   = get_variable('buyersAgent', $_POST);
$bounty        = get_variable('bounty', $_POST);
$bountyAmount  = get_variable('bountyAmount', $_POST);
$bountyPercent = get_variable('bountyPercent', $_POST);
$contingency   = get_variable('contingency', $_POST);
$disclosures   = get_variable('disclosures', $_POST);
$description   = get_variable('description', $_POST);

//Make sure one of the 3 variables are populated
if (empty($idListing)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Listing ID",$return->messages); 
  echo json_encode($return);
  exit;    
}
//Check if Listing ID exists
$table = 'listings';
if (!$return->returnCode == '8') {
//Make sure idListing is valid	
  unset($where);
  $where = add_where("idListing", $idListing, $where);
  $fields = "idListing";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '8';
    $return->messages = add_to_array("message","Invalid idListing $idListing",$return->messages); 
  }

if ($return->returnCode == '8') {
  echo json_encode($return);
  exit; 
}


$where = add_where("idListing", $idListing, $where);
if (isNotEmpty($status)) {
  $update  = add_field("status", $status, $update); 
}
if (isNotEmpty($address)) {
  $update  = add_field("address", $address, $update); 
}
if (isNotEmpty($zip)) {
  $update  = add_field("zip", $zip, $update); 
}
if (isNotEmpty($bedrooms)) {
  $update  = add_field("bedrooms", $bedrooms, $update); 
}
if (isNotEmpty($bathrooms)) {
  $update  = add_field("bathrooms", $bathrooms, $update); 
}
if (isNotEmpty($totalRooms)) {
  $update  = add_field("totalRooms", $totalRooms, $update); 
}
if (isNotEmpty($finishedSqFt)) {
  $update  = add_field("finishedSqFt", $finishedSqFt, $update); 
}
if (isNotEmpty($lotSizeSqFt)) {
  $update  = add_field("lotSizeSqFt", $lotSizeSqFt, $update); 
}
if (isNotEmpty($askingPrice)) {
  $update  = add_field("askingPrice", $askingPrice, $update); 
}
if (isNotEmpty($minimum)) {
  $update  = add_field("minimum", $minimum, $update); 
}
if (isNotEmpty($mls)) {
  $mls         = from_boolian($mls);
  $update  = add_field("mls", $mls, $update); 
}
if (isNotEmpty($buyersAgent)) {
  $buyersAgent = from_boolian($buyersAgent);
  $update  = add_field("buyersAgent", $buyersAgent, $update); 
}
if (isNotEmpty($bounty)) {
  $bounty      = from_boolian($bounty);
  $update  = add_field("bounty", $bounty, $update); 
}
if (isNotEmpty($bountyAmount)) {
  $update  = add_field("bountyAmount", $bountyAmount, $update); 
}
if (isNotEmpty($bountyPercent)) {
  $update  = add_field("bountyPercent", $bountyPercent, $update); 
}
if (isNotEmpty($contingency)) {
  $update  = add_field("contingency", $contingency, $update); 
}
if (isNotEmpty($disclosures)) {
  $update  = add_field("disclosures", $disclosures, $update); 
}
if (isNotEmpty($description)) {
  $update  = add_field("description", $description, $update); 
}

modify_record($table, $update, $where);
$return->returnCode = '0';
$return->messages = add_to_array("message","Successfuly Updated",$return->messages);
  
echo json_encode($return);
?>