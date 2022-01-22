<?php

session_start();
if (isset($_POST["dash"])) {
    header("location: ../teams.php?page=dash");
    exit();
} elseif (isset($_POST["requests"])) {
    header("location: ../teams.php?page=requests");
    exit();
}

else {
    header("location: ../teams.php");
    exit();
}