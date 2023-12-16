<?php

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

  require_once '../app/bootstrap.php';

  // Init Core Library
  $init = new Core;
  // echo "hello world from ...";
?>