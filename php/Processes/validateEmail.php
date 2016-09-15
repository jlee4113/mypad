<?php
require_once('..\utilities\functions.php');
header('Access-Control-Allow-Origin: *');
//echo "started \n";
//these are the input parameters needed
$email     = get_variable('primEmail', $_POST);

$return  = array();
//Make sure primary e-mail is set
if (!empty($email)) {
//  echo "Email Empty \n";
  $return = add_message("returnCode", "8", $return);
  $return = add_message("message", "Email Empty", $return);
  echo json_encode($return);
  exit;   //No email
}


//Check if exists
 $table = 'users';
 $where = add_where("primEmail", $email, $where = array());
 $fields = "primEmail";
 $response = select_from_table($table, $fields, $where);

// Then add the user to the person table with only the e-mail address
if (!empty($response)) {
  echo "Email does not exist \n";
  $return = add_message("returnCode", "1", $return);
  $return = add_message("message", "Email does not exist", $return);
  echo json_encode($return);
  exit;  //Cannot be created.  Password updated
}
else {    //only update password
  echo "Email exists \n";
  $return = add_message("returnCode", "2", $return);
  $return = add_message("message", "Email exists", $return);
  echo json_encode($return);
  exit;  //Cannot be created.  Password updated
}
?>