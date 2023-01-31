<?php

session_start();
require_once '../config/config.inc.php';
require_once 'functions.inc.php';

if (getUserPower(con(), $_SESSION["username"]) < 100) {
    header("location: /?error=noperm");
    exit();
}

if (!isset($_POST["role"]) || $_POST["role"] == "null") {
    header("location: ../admin.php?error=norole&page=roles");
    exit();
}
$id = $_POST["role"];
if (getRole(con(), $id) === false && !isset($_POST["create"])) {
    header("location: ../admin.php?error=norole&page=roles");
    exit();
}
if (roleData(con(), $id)["createdby"] == "root" && !isset($_POST["create"]) && getUserPower(con(), $_SESSION["username"]) < 128) {
    header("location: ../admin.php?error=protrole&page=roles");
    exit();
}
$name = roleData(con(), $id)["name"];
if (!empty($_POST["name"])) {
    $name = $_POST["name"];
}

if (roleData(con(), $id)["power"] >= getUserPower(con(), $_SESSION["username"]) && getUserPower(con(), $_SESSION["username"]) < 127 && !isset($_POST["create"])) {
    header("location: ../admin.php?error=lesspower&page=roles&role=".$id);
    exit();
}

if (isset($_POST["edit"])) {

    $data = roleData(con(), $id);
    if ($data["name"] != $name) {
        editRoleName(con(), $id, $name);
        #logRoleEdit(con(), "Edited roles name of role '".$data["name"]."' to '".$name."'!", $_SESSION["username"]);
    }
    if ($id != $_POST["newh"] && !empty($_POST["newh"]) && $id != 0) {
        editRoleId(con(), $id, $_POST["newh"]);
        $id = $_POST["newh"];
    }

    if (!empty($_POST["power"])) {
        $power = $_POST["power"];
        if ($power >= getUserPower(con(), $_SESSION["username"]) && getUserPower(con(), $_SESSION["username"]) < 128) {
            header("location: ../admin.php?error=lesspower&page=roles&role=".$id);
            exit();
        }
        if ($power > 127 && getUserPower(con(), $_SESSION["username"]) < 128) {
            header("location: ../admin.php?error=toohighpower&page=roles&role=".$id);
            exit();
        }
        if (!($id == 0 && $power < 128)) {
            editRolePower(con(), $id, $power);
        }
        #logRoleEdit(con(), "Edited roles power of role '".$data["name"]."' to '".$power."'!", $_SESSION["username"]);
    }

    setRoleFlags(con(), $id, $_POST["flags"]);

    $_SESSION["admin"] = isAdmin(con(), $_SESSION["username"]);

    header("location: ../admin.php?error=editedrole&page=roles&role=".$id);
    exit();
} elseif (isset($_POST["create"])) {
    if (getRole(con(), $id) !== false) {
        header("location: ../admin.php?error=roleexists&page=roles&create");
        exit(); 
    }
    if (empty($_POST["name"]) || empty($_POST["power"])) {
        header("location: ../admin.php?error=emptyf&page=roles&create");
        exit();
    }

    $power = $_POST["power"];

    if ($power >= getUserPower(con(), $_SESSION["username"]) && getUserPower(con(), $_SESSION["username"]) < 128) {
        header("location: ../admin.php?error=lesspower&page=roles&create");
        exit();
    }

    createRole(con(), $id, $name, $_SESSION["username"], $power);
    #logRoleEdit(con(), "Created role '".$data["name"]."' with name '".$name."', power '".$power."' and id '".$id."'!", $_SESSION["username"]);

    header("location: ../admin.php?error=rolecreated&page=roles&name=".$name);
    exit();
} elseif (isset($_POST["del"])) {
    clearRole(con(), $id);
    delRole(con(), $id);
    #logRoleEdit(con(), "Deleted role '".$data["name"]."'!", $_SESSION["username"]);
    header("location: ../admin.php?error=del&page=roles&name=".$name);
    exit();
}
