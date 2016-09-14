<?php
require_once('..\utilities\functions.php');
header('Access-Control-Allow-Origin: *');
//these are the input parameters needed
//$email = 'david.smith@me.com';
//$password = 'mypassword';

$email     = get_variable('primEmail', $_POST);
$namefirst = get_variable('namefirst', $_POST);
$namelast  = get_variable('namelast', $_POST);
$password  = get_variable('password', $_POST);

//First, check if user already exists
$table = 'users';
$where = add_where("email", $email, $where = array());
$fields = "namelast, namefirst";
$response = select_from_table($table, $fields, $where);

// Then add the user to the person table with only the e-mail address
if (!empty($response)) {
  $record  = array();
  $records = array();
  $record  = add_field("primEmail", $email, $record);
  $record  = add_field("nameFirst", $namefirst, $record);
  $record  = add_field("nameLast", $namelast, $record);
  array_push($records, $record);
  insert_into_table($table, $records);
  // Then select the unique ID that was created in previous step
  unset($where);
  unset($fields);
  unset($response);
  $where = add_where("email", $email, $where = array());
  $fields = "idPerson";
  $response = select_from_table($table, $fields, $where);
  //echo $response;
  $response = json_decode($response, true);
  if (!empty($response)) {
    $id = $response[0]['idPerson'];
  }
//If ID is not set, then exit with message
  if (!isset($id)) {
    echo "E-Mail $email does not exist";
  }  
  // Now add the password to the password table with unique ID assigned
  //Hash the password provided
  $hash = encryptPassword($password);
//If already exists, then update password and if not insert record
  $params = array();
  $response = null;
  $params = add_where('idPerson', $id, $params);
  $response = select_from_table('password', 'idPerson', $params);
//echo $response;
  if (empty(json_decode($response, true))) {
  //Insert
    $record = array();
    $records = array();
    $record = add_field('idPerson', $id, $record);
    $record = add_field('password', $hash, $record);
    $record = add_field('misses', "0", $record);
    $record = add_field('locked', "0", $record);
    array_push($records, $record);
    insert_into_table('password', $records);
}
else {
  //Modify
  $update  = array();
  $where   = array();
  $update  = add_field("password", $hash, $update);
  $update  = add_field("misses", "0", $update);
  $update  = add_field("locked", "0", $update);
  $where   = add_where("idPerson", $id, $where);
  modify_record('password', $update, $where);
}
}
else {    //only update password
  echo 'user exists'.json_encode($response);
}
?>