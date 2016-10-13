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
$idPerson      = get_variable('idPerson', $_POST);
$status        = get_variable('status', $_POST);
$address       = get_variable('address', $_POST);
$zip           = get_variable('zip', $_POST);
$bedrooms      = get_variable('bedrooms', $_POST);
$bathrooms     = get_variable('bathrooms', $_POST);
$totalRooms    = get_variable('totalRooms', $_POST;
$finishedSqFt  = get_variable('finishedSqFt', $_POST);
$lotSizeSqFt   = get_variable('lotSizeSqFt', $_POST);
$askingPrice   = get_variable('askingPrice', $_POST;
$minimum       = get_variable('minimum', $_POST);
$mls           = get_variable('mls', $_POST);
$buyersAgent   = get_variable('buyersAgent', $_POST);
$bounty        = get_variable('bounty', $_POST);
$bountyAmount  = get_variable('bountyAmount', $_POST);
$bountyPercent = get_variable('bountyPercent', $_POST);
$contingency   = get_variable('contingency', $_POST);
$disclosures   = get_variable('disclosures', $_POST);
$description   = get_variable('description', $_POST);


//Make sure the Address is set
if (empty($address)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","Address is Empty",$return->messages); 
  echo json_encode($return);
  exit;    
}


//Make sure the Person ID is set
//if (empty($idPerson)) {
//  $return->returnCode = '8';
//  $return->messages = add_to_array("message","ID for Person is Empty",$return->messages);    
//}

//if (!$return->returnCode == '8') {
//Make sure idPerson is valid	
//  $table = 'users';
//  unset($where);
//  $where = add_where("idPerson", $idHome, $where);
//  $fields = "idPerson";
//  $response = select_from_table($table, $fields, $where);
//  if (empty($response)) {
//    $return->returnCode = '8';
//    $return->messages = add_to_array("message","Invalid idPerson $idPerson",$return->messages); 
//    echo json_encode($return);
//    exit; 
//  }
//}

//if ($return->returnCode == '8') {
//  echo json_encode($return);
//  exit; 
//}

//Add record
$table = 'listings';

// change boolians
$buyersAgent = from_boolian($buyersAgent);
$bounty      = from_boolian($bounty);
$mls         = from_boolian($mls);

// Parameters to update
$record  = add_field("idPerson", $idHome, $record);
$record  = add_field("status", $status, $record);
$record  = add_field("address", $address, $record);
$record  = add_field("zip", $zip, $record);
$record  = add_field("bedrooms", $bedrooms, $record);
$record  = add_field("bathrooms", $bathrooms, $record);
$record  = add_field("totalRooms", $totalRooms, $record);
$record  = add_field("finishedSqFt", $finishedSqFt, $record);
$record  = add_field("lotSizeSqFt", $lotSizeSqFt, $record);
$record  = add_field("askingPrice", $askingPrice, $record);
$record  = add_field("minimum", $minimum, $record);
$record  = add_field("mls", $mls, $record);
$record  = add_field("buyersAgent", $buyersAgent, $record);
$record  = add_field("bounty", $bounty, $record);
$record  = add_field("bountyAmount", $bountyAmount, $record);
$record  = add_field("bountyPercent", $bountyPercent, $record);
$record  = add_field("contingency", $contingency, $record);
$record  = add_field("disclosures", $disclosures, $record);
$record  = add_field("description", $description, $record);
array_push($records, $record);
//Home ID to update
$response = insert_into_table($table, $records);
$return->data = add_to_array("idListing",$response,$return->data);

//Set success return code
$return = add_message("returnCode", "0", $return);
$return = add_message("message", "Listing successfully created", $return);

echo json_encode($return);
?>