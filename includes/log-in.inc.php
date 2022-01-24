<?php

if (isset($_POST["submit"])) {

  $name = $_POST["name"];
  $pw = $_POST["pw"];

  require_once '../config/dbh.inc.php';
  require_once 'functions.inc.php';

  if (emptyInputLogin($name, $pw) !== false) {
    header("location: ../log-in.php?error=emptyinput");
    exit();
  }

  loginUser($con, $name, $pw);
}
else {
  header("location: ../index.php?error=notfromsubmit");
  exit();
}
