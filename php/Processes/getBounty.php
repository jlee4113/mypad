<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');
//Declarations
$return = new json();
$record  = array();
$records = array();
$where   = array();

//these are the input parameters needed
$idBounty  = get_variable('idBounty', $_GET);
$idListing = get_variable('idListing', $_GET);
$idPerson  = get_variable('idPerson', $_GET);

if (!empty($idBounty)) {
  $where = add_where("idBounty", $idBounty, $where);
}
ELSE {
  if (!empty($idListing)) {
    $where = add_where("idListing", $idListing, $where);
  }
  if (!empty($idPerson)) {
    $where = add_where("idPerson", $idPerson, $where);
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
$table = 'bounty';
$query = select_from_table($table, '*', $where);
$return->data = $query;

if (empty($return->data)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Listings selected",$return->messages);  
}
else {
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Listings Selected",$return->messages);  
}
echo json_encode($return);
?>