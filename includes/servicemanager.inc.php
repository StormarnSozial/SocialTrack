<?php

session_start();
require_once '../config/dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["create"])) {
    $name = $_POST["name"];
    $index = $_POST["index"];
    if (empty($name) || empty($index)) {
        header("location: ../admin.php?page=teams&createservice&error=emptyf");
        exit();
    }
    if (strlen($name) > 64) {
        header("location: ../admin.php?page=teams&createservice&error=toolong");
        exit();
    }
    createService(con(), $name, $index);
    header("location: ../admin.php?page=teams&error=c&service=".serviceDataByIndex(con(), $index)["id"]);
    exit();
} elseif (isset($_POST["edit"])) {
    $id = $_POST["service"];
    $data = serviceData(con(), $id);
    if (!empty($_POST["name"]) && strlen($_POST["name"]) < 65) {
        editServiceName(con(), $id, $_POST["name"]);
    }
    if (!empty($_POST["index"]) && serviceDataByIndex(con(), $_POST["index"]) === false) {
        editServiceIndex(con(), $id, $_POST["index"]);
    }
    header("location: ../admin.php?page=teams&error=edited&service=".$id);
    exit();
} elseif (isset($_POST["del"])) {
    $id = $_POST["service"];
    $data = serviceData(con(), $id);
    delService(con(), $id);
    header("location: ../admin.php?page=teams&error=delservice&service=".$data["name"]);
    exit();
}