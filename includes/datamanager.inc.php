<?php

use JetBrains\PhpStorm\NoReturn;

session_start();
require_once '../config/config.inc.php';
require_once 'functions.inc.php';
/**
 * @return void
 */
function appendFilters(): void
{
    $filters = "";
    if (!empty($_SESSION["fteam"])) {
        $filters = "?fteam=" . $_SESSION["fteam"];
    }
    if (!empty($_SESSION["fuser"])) {
        $filters = $filters . "&fuser=" . $_SESSION["fuser"];
    }
    header("location: ../datacenter.php" . $filters);
    exit();
}

if (isset($_GET["sign"])) {
    signData(con(), $_GET["sign"]);
} else if (isset($_GET["unsign"])) {
    unsignData(con(), $_GET["unsign"]);
}
$datac = isset($_POST["datac"]);
$page = "profile";
if ((isset($_POST["edit"]) && $_POST["edit"] == "datac") || (isset($_POST["del"]) && $_POST["del"] == "datac")) {
    $page = "datacenter";
}
if (isset($_POST["add"])) {
    if (empty($_POST["name"]) || empty($_POST["lessons"]) || empty($_POST["date"]) || $_POST["team"] == "null") {
        header("location: ../".$page.".php?error=emptyf&create");
        exit();
    }
    if (invalidName($_POST["name"])) {
        header("location: ../".$page.".php?error=invalid&create");
        exit();
    }
    createData(con(), $_POST["name"], $_SESSION["username"], $_POST["lessons"], $_POST["date"], $_POST["team"]);
    updateUserLessons(con(), $_SESSION["username"]);
} elseif (isset($_POST["del"])) {
    if (empty($_POST["id"])) {
        header("location: ../".$page.".php?error=eemptyf");
        exit();
    }
    $data = dataData(con(), $_POST["id"]);
    if ($datac != "profile") {
        sendNotification(con(), $data["account"], "root", "Event gelöscht!", "Dein Event '".$data["name"]."' wurde von '".$_SESSION["username"]."' gelöscht!");
    }
    updateUserLessons(con(), $_SESSION["username"]);
    delData(con(), $_POST["id"]);
    if ($_POST["del"] == "datac") {
        $page = "datacenter";
    }
    header("location: ../".$page.".php?error=deldata&name=".$data["name"]);
    exit();
} elseif (isset($_POST["edit"])) {
    $firstData = dataData(con(), $_POST["id"]);

    if (!empty($_POST["name"]) && !invalidName($_POST["name"])) {
        editDataName(con(), $_POST["id"], $_POST["name"]);
    }

    if (!empty($_POST["date"])) {
        editDataDate(con(), $_POST["id"], $_POST["date"]);
    }

    if (!empty($_POST["lessons"])) {
        editDataDuration(con(), $_POST["id"], $_POST["lessons"]);
    }

    if ($_POST["team"] !== "null") {
        editDataTeam(con(), $_POST["id"], $_POST["team"]);
    }

    updateUserLessons(con(), $_SESSION["username"]);
    $data = dataData(con(), $_POST["id"]);
    if ($datac) {
        sendNotification(con(), $data["account"], "root", "Event bearbeitet!", "Dein Event '".$data["name"]."' wurde von '".$_SESSION["username"]."' bearbeitet!");
    }
    $lastData = dataData(con(), $_POST["id"]);
    if ($firstData !== $lastData) {
        unsignData(con(), $_POST["id"]);
    }
    header("location: ../".$page.".php?error=dataedited&data=".$_POST["id"]);
    exit();
}
else {
    header("location: ../".$page.".php?error=notfromsubmit");
}