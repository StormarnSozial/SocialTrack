<?php
session_start();
require_once '../config/config.inc.php';
require_once '../includes/functions.inc.php';

if (!isset($_SESSION["username"]) || userData(con(), $_SESSION["username"]) === false) {
    exit();
} elseif (isset($_GET["setting"]) && isset(getSettings()[$_GET["setting"]])) {
    $set = $_GET["setting"];
    mirrorSetting($_SESSION["username"], $set);
} else {
    header("location: ../?error=notFromSubmit");
}
exit();