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
//display images
while($row=mysql_fetch_array($return)) {
 $image_name=$row["link"];
 $image_path=$row["folder"];
 $return->data = add_to_array("picture","img src=".$image_path."/".$image_name." border="0"",$return->data);
 echo "img src=".$image_path."/".$image_name." width=100 height=100";
}  
}
echo json_encode($return);
?>