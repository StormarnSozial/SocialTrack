<?php

session_start();
if (isset($_POST["adminlogout"])) {
    $_SESSION["adminentry"] = false;
    header("location: ../admin.php?error=loggedout");
    exit();
} elseif (isset($_POST["roles"])) {
    header("location: ../admin.php?page=roles");
    exit();
} elseif (isset($_POST["users"])) {
    header("location: ../admin.php?page=users");
    exit();
} elseif (isset($_POST["groups"])) {
    header("location: ../admin.php?page=groups");
    exit();
} elseif (isset($_POST["news"])) {
    header("location: ../admin.php?page=news");
    exit();
} elseif (isset($_POST["teams"])) {
    header("location: ../admin.php?page=teams");
    exit();
}

else {
    header("location: ../admin.php");
    exit();
}