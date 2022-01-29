<?php

session_start();
if (isset($_POST["dash"])) {
    header("location: ../teams.php?page=dash");
} elseif (isset($_POST["requests"])) {
    header("location: ../teams.php?page=requests");
}

else {
    header("location: ../teams.php");
}
exit();