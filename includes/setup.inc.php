<?php

require_once '../config/dbh.inc.php';
require_once 'functions.inc.php';

if (!isset($_POST["setup"])) {
    header("location: ../?notfromsubmit");
    exit();
}

if (empty($_POST["aname"]) || empty($_POST["apw"])) {
    header("location: ../setup.php?error=emptyf");
    exit();
}

$name = $_POST["aname"];
$pw = $_POST["apw"];

if (strlen($name) > 64) {
    header("location: ../setup.php?error=toolong");
    exit();
}

if (invalidName($name)) {
    header("location: ../setup.php?error=nameinvalid");
    exit();
}

fullSetup($con, $name, $pw);
