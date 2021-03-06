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
$idPicture    = get_variable('idPicture', $_POST);
$idListing    = get_variable('idListing', $_POST);

//Make sure the Persons ID is set
if (empty($idPicture) && empty($idListing)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Picture and Listing are empty",$return->messages);
  echo json_encode($return);
  exit; 
}

//Check if the Home ID is valid
$table = 'pictures';
if (!empty($idPicture)) {
  $where = add_where("idPicture", $idPicture, $where);
}
else {
  if (!empty($idListing)) {
    $where = add_where("idListing", $idListing, $where);
  }	
}

//Delete Record
$rows = delete_from_table($table, $where);
$return->data = add_to_array("Deleted",$rows,$return->data);
//Set success return code
IF ($rows >  '0') {
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Picture(s) successfully deleted",$return->messages);
}
ELSE {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Bounty found to delete in the selection criteria",$return->messages);  
} 	
echo json_encode($return);
?>