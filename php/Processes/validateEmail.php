<?php
require_once('..\utilities\functions.php');
header('Access-Control-Allow-Origin: *');
//these are the input parameters needed
//$email = 'david.smith@me.com';
$email     = get_variable('primEmail', $_POST);

//Make sure primary e-mail is set
if (!empty($email)) {
  exit(1);   //No email
}


//Check if exists
$table = 'users';
$where = add_where("primEmail", $email, $where = array());
$fields = "primEmail";
$response = select_from_table($table, $fields, $where);

// Then add the user to the person table with only the e-mail address
if (!empty($response)) {
  echo 'Email does not exist';
  exit(1);  //Cannot be created.  Password updated
else {    //only update password
  echo 'Email exists';
  exit(2);  //Cannot be created.  Password updated
}
?>