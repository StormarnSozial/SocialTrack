<?php

session_start();
require_once '../config/config.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["add"])) {
    $user = $_POST["user"];
    $group = $_POST["group"];
    addGrouper(con(), $group, $user);
    header("location: ../admin.php?error=addedgrouper&page=groups&gid=".$group."&usr=".$user);
    exit();
} elseif (isset($_POST["del"])) {
    $user = $_POST["del"];
    $group = $_POST["group"];
    delGrouper(con(), $group, $user);
    header("location: ../admin.php?error=delgrouper&page=groups&gid=".$group."&usr=".$user);
    exit();
}

if (!isset($_POST["name"])) {
    header("location: ../?error=notfromsubmit");
    exit();
}

$acc = $_POST["group"] ?? $_POST["name"];
$acc = strtolower($acc);
$name = $_POST["name"];

if (isset($_POST["create"])) {
    if (empty($acc) || empty($name)) {
        header("location: ../admin.php?error=emptyf&create&page=groups");
        exit();
    }
    if (groupData(con(), $acc) !== false) {
        header("location: ../admin.php?error=exists&create&page=groups");
        exit();
    }
    createGroup(con(), $acc, $name, $_SESSION["username"]);
    header("location: ../admin.php?error=created&gid=".groupData(con(), $acc)["id"]."&page=groups");
    exit();
} elseif (isset($_POST["edit"])) {
    $data = groupData(con(), $acc);
    $newacc = $_POST["newacc"];
    if ($data === false) {
        header("location: ../admin.php?page=groups&error=norole");
        exit();
    }
    if ($data["account"] != $newacc) {
        editGroupAccount(con(), $data["id"], $newacc);
    }
    if ($data["name"] != $name) {
        editGroupName(con(), $data["id"], $name);
    }
    header("location: ../admin.php?error=edited&page=groups&gid=".$data["id"]);
    exit();
} elseif (isset($_POST["delgroup"])) {
    $data = groupData(con(), $acc);
    clearGroup(con(), $data["id"]);
    delGroup(con(), $data("id"));
    header("location: ../admin.php?error=del&page=groups&name=".$data["name"]);
    exit();
}
