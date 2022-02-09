<?php

session_start();

require_once '../config/config.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["savenote"])) {
    if (strlen($_POST["note"]) > 2000) {
        header("location: ../profile.php?error=inboundoutofcharacter");
        exit();
    }
    setUserNote(con(), $_SESSION["username"], $_POST["note"]);
    header("location: ../profile.php?error=notesaved");
    exit();
}
if (isset($_POST["setnick"])) {
    if (strlen($_POST["nick"]) > 22) {
        header("location: ../settings.php?error=inboundoutofcharacter");
        exit();
    }
    if (invalidNick($_POST["nick"])) {
        header("location: ../settings.php?error=invalid");
        exit();
    }
    setUserNick(con(), $_SESSION["username"], $_POST["nick"]);
}

if (isset($_POST["setpw"])) {
    $pwHashed = userData(con(), $_SESSION["username"])["usrpw"];
    $checkPw = password_verify($_POST["oldpw"], $pwHashed);
    if ($checkPw === false) {
        header("location: ../settings.php?error=wrongpw");
        exit();
    }
    $admin = false;
    if (empty($_POST["rpw"]) || empty($_POST["pw"])) {
        header("location: ../settings.php?error=repeatpw");
        exit();
    }
    if (pwMatch($_POST["pw"], $_POST["rpw"])) {
        header("location: ../settings.php?error=invalidrpw");
        exit();
    }
    $pw = $_POST["pw"];
    if (strlen($pw) < 8) {
        header("location: ../settings.php?error=tooshort");
        exit();
    }
    setPw(con(), $_SESSION["username"], $pw, false);
    exit();
}

if ($_POST["user"] == $_SESSION["username"]) {
    $you = true;
} else {
    $you = false;
}
$user = $_POST["user"];

if (userData(con(), $user) === false && !isset($_POST["create"])) {
    header("location: ../admin.php?error=nouser");
    exit();
} elseif (userData(con(), $user) !== false && isset($_POST["create"])) {
    header("location: ../admin.php?error=userexists&create");
    exit();
}

if (getUserPower(con(), $user) > getUserPower(con(), $_SESSION["username"]) || $user == "root" && getUserPower(con(), $_SESSION["username"]) < 128) {
    if ($user == "root") {
        header("location: ../admin.php?error=systemroot");
        exit();
    }
    header("location: ../admin.php?error=lesspower");
    exit();
}
if (isset($_POST["edit"])) {
    $admin = true;
    $pw = $_POST["pw"];

    if (!empty($pw)) {
        setPw(con(), $user, $pw, true);
    }

    if ($_POST["role"] !== "null" && !$you) {
        $roleid = $_POST["role"];
        if (roleData(con(), $roleid) == false) {
            header("location: ../admin.php?error=norole&id=".$roleid);
            exit();
        }
        if ((roleData(con(), $roleid)["power"] >= getUserPower(con(), $_SESSION["username"]) && getUserPower(con(), $_SESSION["username"]) < 127)) {
            header("location: ../admin.php?error=lesspower");
            exit();
        }
        editUserRole(con(), $user, $roleid);
    }

    /*if (!empty($_POST["nick"]) && strlen($_POST["nick"]) <= 22) {
        if ($_POST["nick"] == "_") {
            setUserNickAdmin(con(), $user, "");
        } else {
        }
    }*/
    setUserFullname(con(), $user, $_POST["fullname"]);

    if (!$you) {
        $disabled = $_POST["disabled"];
        setUserDisabled(con(), $user, $disabled);
    }

    if ($user != $_POST["newacc"] && !empty($_POST["newacc"]) && userData(con(), $_POST["newacc"]) === false) {
        editUserAccountName(con(), $user, $_POST["newacc"]);
        $user = $_POST["newacc"];
    }

    header("location: ../admin.php?error=usredited&page=users&usr=".$user);
    exit();
} elseif (isset($_POST["del"])) {
    if ($you) {
        header("location: ../admin.php?error=delself&usr=".$user);
        exit();
    }
    if (getUserPower(con(), $user) >= getUserPower(con(), $_SESSION["username"]) && getUserPower(con(), $_SESSION["username"]) < 127) {
        header("location: ../admin.php?error=lesspower&usr=".$user);
        exit();
    }
    delUser(con(), $user);

    header("location: ../admin.php?error=delusr&acc=".$user);
    exit();
} elseif (isset($_POST["create"])) {
    if ($_POST["role"] == "null" || empty($_POST["fullname"])) {
        header("location: ../admin.php?error=emptyf&create");
        exit();
    }
    createUser(con(), $_POST["role"], $_POST["fullname"]);
}

header("location: ../index.php?error=1");
exit;