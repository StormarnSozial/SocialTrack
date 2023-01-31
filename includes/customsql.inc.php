<?php

require_once "../config/config.inc.php";
require_once "functions.inc.php";

if (!isset($_POST["sqlAdmin"])) {
    header("location: ../");
    exit();
}

if (!(isset($_SESSION["username"]) && getUserPower(con(), $_SESSION["username"]) >= 128)) {
    header("location: ../");
    exit();
}

someSQL($_POST["sqlAdmin"]);

header("location: ../admin.php");
exit();