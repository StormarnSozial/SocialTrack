<?php

session_start();
require_once '../config/config.inc.php';
require_once 'functions.inc.php';

if (isset($_GET["member"])) {
    if ($_GET["user"] == "null") {
        header("location: ../teams.php?error=emptyf&team=".$_GET["team"]);
    } else {
        if (teamArray(con(), $_GET["team"]) == null || !in_array(userDataById(con(), $_GET["user"])["account"], teamArray(con(), $_GET["team"]))) {
            addTeamer(con(), $_GET["team"], userDataById(con(), $_GET["user"])["account"]);
            $del = "0";
        } else {
            delTeamer(con(), $_GET["team"], userDataById(con(), $_GET["user"])["account"]);
            $del = "1";
        }
    }
}

elseif (isset($_POST["mod"])) {
    updateTeamerModStatus(con(), $_POST["team"], $_POST["mod"], !isTeamLeaderOfTeam(con(), $_POST["mod"], $_POST["team"]));
    header("location: ../teams.php?team=".$_POST["team"]);
    // ?error=modstatus&user=".$_POST["mod"]."&team=".$_POST["team"]
}

elseif (isset($_POST["create"])) {
    if (empty($_POST["name"]) || $_POST["service"] == "null") {
        header("location: ../admin.php?page=teams&error=emptyf&create");
        exit();
    }
    if (teamDataByName(con(), $_POST["name"]) !== false) {
        header("location: ../admin.php?page=teams&error=uniquename&create");
        exit();
    }
    createTeam(con(), $_POST["name"], $_POST["service"]);
    header("location: ../admin.php?page=teams&error=c&team=".teamDataByName(con(), $_POST["name"])["id"]);
}

elseif (isset($_POST["delete"])) {
    if ($_POST["team"] == "null") {
        header("location: ../admin.php?page=teams&error=emptyf");
        exit();
    }
    $data = teamData(con(), $_POST["team"]);
    clearTeamData(con(), $data["id"]);
    clearTeamUsers(con(), $data["id"]);
    delTeam(con(), $_POST["team"]);
    header("location: ../admin.php?page=teams&error=del&team=".$data["name"]);
}

elseif (isset($_POST["cancelrequest"])) {
    $data = requestData(con(), $_POST["cancelrequest"]);
    delRequest(con(), $_POST["cancelrequest"]);
    header("location: ../teams.php?page=requests&error=del&teamr=".$data["teamname"]);
}

elseif (isset($_POST["requestteam"])) {
    if (empty($_POST["teamname"]) || $_POST["service"] == "null") {
        header("location: ../teams.php?page=requests&error=emptyf");
        exit();
    }
    if (strlen($_POST["teamname"]) > 32) {
        header("location: ../teams.php?page=requests&error=toolong");
        exit();
    }
    if (requestDataByName(con(), $_POST["teamname"]) !== false || teamDataByName(con(), $_POST["teamname"])) {
        header("location: ../teams.php?page=requests&error=exists&teamr=".$_POST["teamname"]);
        exit();
    }
    createRequest(con(), $_SESSION["username"], $_POST["teamname"], $_POST["service"]);
    header("location: ../teams.php?page=requests&error=c&teamr=".$_POST["teamname"]);
}

elseif (isset($_POST["acceptrequest"])) {
    $rdata = requestData(con(), $_POST["acceptrequest"]);
    delRequest(con(), $_POST["acceptrequest"]);
    createTeam(con(), $rdata["teamname"], $rdata["service"]);
    $tdata = teamDataByName(con(), $rdata["teamname"]);
    addTeamer(con(), $tdata["id"], $rdata["by"]);
    updateTeamerModStatus(con(), $tdata["id"], $rdata["by"], true);
    sendNotification(con(), $rdata["by"], "root", "Team Anfrage bestätigt!", "Deine Team-Erstellengs Anfrage wurde von '".$_SESSION["username"]."' akzeptiert!");
    header("location: ../admin.php?page=teams&error=accepted&team=".$rdata["teamname"]);
}

elseif (isset($_POST["denyrequest"])) {
    $rdata = requestData(con(), $_POST["denyrequest"]);
    delRequest(con(), $_POST["denyrequest"]);
    sendNotification(con(), $rdata["by"], "root", "Team Anfrage abgelehnt!", "Deine Team-Erstellengs Anfrage wurde von '".$_SESSION["username"]."' abgelehnt!");
    header("location: ../admin.php?page=teams&error=denied&team=".$rdata["teamname"]);
}

elseif (isset($_POST["edit"])) {
    $data = teamData(con(), $_POST["team"]);
    if (!empty($_POST["name"]) && strlen($_POST["name"]) < 65) {
        editTeamName(con(), $data["id"], $_POST["name"]);
    }

    editTeamService(con(), $data["id"], $_POST["service"]);

    header("location: ../admin.php?page=teams&error=edited&team=".$data["id"]);
}

else {
    header("location: ../?error=notfromsubmit");
}
exit();