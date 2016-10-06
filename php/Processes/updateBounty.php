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
$idBounty  = get_variable('idBounty', $_POST);
$idStatus  = get_variable('idStatus', $_POST);

//Make sure one of the 3 variables are populated
if (empty($idBounty)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","No Bounty ID",$return->messages); 
  echo json_encode($return);
  exit;    
}
//Check if Bounty ID exists
$table = 'bounty';
$where = add_where("idBounty", $idBounty, $where);
$update  = add_field("status", $status, $update);
modify_record('password', $update, $where);
$return->returnCode = '0';
$return->messages = add_to_array("message","Successfuly Updated",$return->messages);
  
echo json_encode($return);
?>