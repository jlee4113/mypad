<?php
  require_once('../utilities/functions.php');
  header('Access-Control-Allow-Origin: *');
  echo json_encode(list_proc_files());
?>