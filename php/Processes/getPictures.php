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
$idListing = get_variable('idHome', $_GET);
$idPicture = get_variable('idPicture', $_GET);

if (!empty($idListing)) {
  $where = add_where("idListing", $idListing, $where);
}
if (!empty($idPicture)) {
  $where = add_where("idPicture", $idPicture, $where);
}

//Make sure there is a selection criteria given
if (empty($where)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Selection given",$return->messages);  
  echo json_encode($return);
  exit; 
}
//Select data
$table = 'pictures';
$query = select_from_table($table, '*', $where);
$return->data = $query;
if (empty($return->data)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Pictures selected",$return->messages);  
}
else {
  $return->returnCode = '0';
  $return->messages = add_to_array("message","Pictures Selected",$return->messages);  
}
echo json_encode($return);
?>