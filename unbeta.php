<?php
require_once "includes/require.php";

if (isset($_SESSION["username"])) {
    mirrorSetting($_SESSION["username"], "beta");
}
header("location: ./");