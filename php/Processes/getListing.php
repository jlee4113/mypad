<?php
require_once('../utilities/functions.php');
include('../utilities/globals.php');
header('Access-Control-Allow-Origin: *');
//Declarations
$return = new json();
$record  = array();
$records = array();

//these are the input parameters needed
$idListing = get_variable('idHome', $_GET);
$idPerson  = get_variable('idPerson', $_GET);
$and       = " and ";
if (!empty($idListing)) {
  $where = add_where("idListing", $idListing, $where);

}
if (!empty($idPerson)) {
  $where = add_where("idPerson", $idPerson, $where);
}

//Make sure there is a selection criteria given
if (empty($where)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Selection given",$return->messages);  
  echo json_encode($return);
  exit; 
}
//Select data
$table = 'listings';
$query = select_from_table($table, '*', $where);
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