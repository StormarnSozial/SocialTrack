<?php

session_start();
if (isset($_POST["adminlogout"])) {
    $_SESSION["adminentry"] = false;
    header("location: ../admin.php?error=loggedout");
} elseif (isset($_POST["roles"])) {
    header("location: ../admin.php?page=roles");
} elseif (isset($_POST["users"])) {
    header("location: ../admin.php?page=users");
} elseif (isset($_POST["groups"])) {
    header("location: ../admin.php?page=groups");
} elseif (isset($_POST["news"])) {
    header("location: ../admin.php?page=news");
} elseif (isset($_POST["teams"])) {
    header("location: ../admin.php?page=teams");
}

else {
    header("location: ../admin.php");
}
exit();