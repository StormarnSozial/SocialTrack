<?php

session_start();
if (isset($_POST["adminlogout"])) {
    $_SESSION["adminentry"] = false;
    header("location: ../admin.php?error=loggedout");
} elseif (isset($_GET["roles"])) {
    header("location: ../admin.php?page=roles");
} elseif (isset($_GET["users"])) {
    header("location: ../admin.php?page=users");
} elseif (isset($_GET["groups"])) {
    header("location: ../admin.php?page=groups");
} elseif (isset($_GET["news"])) {
    header("location: ../admin.php?page=news");
} elseif (isset($_GET["teams"])) {
    header("location: ../admin.php?page=teams");
}

else {
    header("location: ../admin.php");
}
exit();