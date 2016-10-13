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
$idListing      = get_variable('idListing', $_POST);
//$link           = get_variable('link', $_POST);


//Make sure the Person ID is set
if (empty($idListing)) {
  $return->returnCode = '8';
  $return->messages = add_to_array("message","ID for Listing is Empty",$return->messages);    
}

//Validate the listing exists
if (!$return->returnCode == '8') {
//Make sure idPerson is valid	
  $table = 'listings';
  unset($where);
  $where = add_where("idListing", $idListing, $where);
  $fields = "idListing";
  $response = select_from_table($table, $fields, $where);
  if (empty($response)) {
    $return->returnCode = '8';
    $return->messages = add_to_array("message","Invalid idListing $idListing",$return->messages); 
  }
}
if ($return->returnCode == '8') {
  echo json_encode($return);
  exit; 
}

//Add image to the file location
$upload_image=$_FILES[" myimage "][ "name" ];
$folder="/images/";
move_uploaded_file($_FILES[" myimage "][" tmp_name "], "$folder".$_FILES[" myimage "][" name "]);

//Add record
$table = 'pictures';

// Parameters to update
$record  = add_field("idListing", $idListing, $record);
$record  = add_field("folder", $folder, $record);
$record  = add_field("link", $upload_image, $record);
array_push($records, $record);
//Picture ID to update
$response = insert_into_table($table, $records);
$return->data = add_to_array("idPicture",$response,$return->data);
$return->data = add_to_array("link",$upload_image,$return->data);

//Set success return code
$return = add_message("returnCode", "0", $return);
$return = add_message("message", "Picture Link successfully created", $return);

echo json_encode($return);
?>