<?php
require_once "../group2/includes/load.php";
require_once "../group2/includes/dbcon.php";


session_destroy();
  if(!$session->logout()) {redirect("../group2/index.php");}

exit;
?>