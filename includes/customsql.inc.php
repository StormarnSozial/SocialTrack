<?php

require_once "../config/dbh.inc.php";
require_once "functions.inc.php";

if (!isset($_POST["sqlAdmin"])) {
    header("location: ../");
    exit();
}

someSQL($_POST["sqlAdmin"]);

header("location: ../admin.php");
exit();