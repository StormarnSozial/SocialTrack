<?php
session_start();
require_once '../config/config.inc.php';
require_once '../includes/functions.inc.php';

if (isset($_POST["setting"])) {
    $set = $_POST["setting"];
    mirrorSetting($_SESSION["username"], $set);
    header("location: ../settings.php");
} else {
    header("location: ../?error=notFromSubmit");
}
exit();