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
$idBounty  = get_variable('idBounty', $_POST);
$idListing = get_variable('idListing', $_POST);
$idPerson  = get_variable('idPerson', $_POST);

//Make sure one of the 3 variables are populated
if (empty($idBounty && empty($idListing) && empty($idPerson))) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No information provided",$return->messages); 
  echo json_encode($return);
  exit;    
}




//Delete record
$table = 'bounty';
if (!empty($idBounty)) {
  $where = add_where("idHome", $idBounty, $where);
}
else {
  if (!empty($idListing)) {
    $where = add_where("idListing", $idListing, $where);
  }
  if (!empty($idPerson)) {
    $where = add_where("idPerson", $idPerson, $where);
  }  
}

//Delete Record
delete_from_table($table, $where);

//Set success return code
$return->returnCode = '0';
$return->messages = add_to_array("message","Bounty(s) successfully deleted",$return->messages);  
echo json_encode($return);
?>