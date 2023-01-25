<?php

if (isset($_COOKIE['remember'])) {
    unset($_COOKIE['remember']);
    setcookie('remember', null, -1, '/');
}
session_start();
session_unset();
session_destroy();

header("location: ../index.php?error=loggedout");
exit();
