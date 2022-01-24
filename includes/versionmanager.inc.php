<?php

session_start();
require_once '../config/dbh.inc.php';
require_once 'functions.inc.php';

if (!isset($_POST["add"])) {
    header("location: ../?error=notfromsubmit");
    exit();
}

if ((empty($_POST["1"]) || empty($_POST["2"]) || empty($_POST["3"]) || empty($_POST["des"])) && $_POST["1"] != 0 && $_POST["2"] != 0 && $_POST["3"] != 0) {
    header("location: ../admin.php?error=emptyf&page=updates");
    exit();
}

$version = $_POST["1"].".".$_POST["2"].".".$_POST["3"];

createVersion($con, $version, $_POST["des"]);