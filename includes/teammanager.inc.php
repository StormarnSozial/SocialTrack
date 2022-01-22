<?php

session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["member"])) {
    if ($_POST["user"] == "null") {
        header("location: ../teams.php?error=emptyf&team=".$_POST["team"]);
        exit();
    } else {
        if (teamArray($con, $_POST["team"]) == null || !in_array($_POST["user"], teamArray($con, $_POST["team"]))) {
            addTeamer($con, $_POST["team"], $_POST["user"]);
            $del = "0";
        } else {
            delTeamer($con, $_POST["team"], $_POST["user"]);
            $del = "1";
        }
        header("location: ../teams.php?error=memberstatus&user=".$_POST["user"]."&team=".$_POST["team"]."&del=".$del);
        exit();
    }
} elseif (isset($_POST["mod"])) {
    if ($_POST["user"] == "null") {
        header("location: ../teams.php?error=emptyf&team=".$_POST["team"]);
        exit();
    } else {
        updateTeamerModStatus($con, $_POST["team"], $_POST["user"], !isTeamLeaderOfTeam($con, $_POST["user"], $_POST["team"]));
        header("location: ../teams.php?error=modstatus&user=".$_POST["user"]."&team=".$_POST["team"]);
        exit();
    }
} elseif (isset($_POST["create"])) {
    if (empty($_POST["name"])) {
        header("location: ../admin.php?page=teams&error=emptyf");
        exit();
    }
    createTeam($con, $_POST["name"]);
    header("location: ../admin.php?page=teams&error=c&team=".$_POST["name"]);
    exit();
} elseif (isset($_POST["delete"])) {
    if ($_POST["team"] == "null") {
        header("location: ../admin.php?page=teams&error=emptyf");
        exit();
    }
    $data = teamData($con, $_POST["team"]);
    clearTeamData($con, $data["id"]);
    clearTeamUsers($con, $data["id"]);
    delTeam($con, $_POST["team"]);
    header("location: ../admin.php?page=teams&error=del&team=".$data["name"]);
    exit();
} elseif (isset($_POST["cancelrequest"])) {
    $data = requestData($con, $_POST["cancelrequest"]);
    delRequest($con, $_POST["cancelrequest"]);
    header("location: ../teams.php?page=requests&error=del&teamr=".$data["teamname"]);
    exit();
} elseif (isset($_POST["requestteam"])) {
    if (empty($_POST["teamname"])) {
        header("location: ../teams.php?page=requests&error=emptyf");
        exit();
    }
    if (strlen($_POST["teamname"]) > 32) {
        header("location: ../teams.php?page=requests&error=toolong");
        exit();
    }
    if (requestDataByName($con, $_POST["teamname"]) !== false || teamDataByName($con, $_POST["teamname"])) {
        header("location: ../teams.php?page=requests&error=exists&teamr=".$_POST["teamname"]);
        exit();
    }
    createRequest($con, $_SESSION["username"], $_POST["teamname"]);
    header("location: ../teams.php?page=requests&error=c&teamr=".$_POST["teamname"]);
    exit();
} elseif (isset($_POST["acceptrequest"])) {
    $rdata = requestData($con, $_POST["acceptrequest"]);
    delRequest($con, $_POST["acceptrequest"]);
    createTeam($con, $rdata["teamname"]);
    $tdata = teamDataByName($con, $rdata["teamname"]);
    addTeamer($con, $tdata["id"], $rdata["by"]);
    updateTeamerModStatus($con, $tdata["id"], $rdata["by"], true);
    sendNotification($con, $rdata["by"], "root", "Team Anfrage bestätigt!", "Deine Team-Erstellengs Anfrage wurde von '".$_SESSION["username"]."' akzeptiert!");
    header("location: ../admin.php?page=teams&error=accepted&team=".$rdata["teamname"]);
    exit();
} elseif (isset($_POST["denyrequest"])) {
    $rdata = requestData($con, $_POST["denyrequest"]);
    delRequest($con, $_POST["denyrequest"]);
    sendNotification($con, $rdata["by"], "root", "Team Anfrage abgelehnt!", "Deine Team-Erstellengs Anfrage wurde von '".$_SESSION["username"]."' abgelehnt!");
    header("location: ../admin.php?page=teams&error=denied&team=".$rdata["teamname"]);
    exit();
}

else {
    header("location: ../?error=notfromsubmit");
    exit();
}