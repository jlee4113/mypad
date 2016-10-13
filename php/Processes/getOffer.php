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
$idOffer   = get_variable('idOffer', $_GET);
$idListing = get_variable('idListing', $_GET);
$idBuyer   = get_variable('idBuyer', $_GET);

if (!empty($idOffer)) {
  $where = add_where("idOffer", $idListing, $where);
}
if (!empty($idListing)) {
  $where = add_where("idListing", $idListing, $where);
}
if (!empty($idBuyer)) {
  $where = add_where("idBuyer", $idPerson, $where);
}

//Make sure there is a selection criteria given
if (empty($where)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Selection given",$return->messages);  
  echo json_encode($return);
  exit; 
}
//Select data
$table = 'offers';
$query = select_from_table($table, '*', $where);
$return->data = $query;
if (empty($return->data)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Offers selected",$return->messages);  
}
else {
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Offers Selected",$return->messages);  
}
echo json_encode($return);
?>