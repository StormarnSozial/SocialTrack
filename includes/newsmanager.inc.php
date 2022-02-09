<?php

session_start();
require_once '../config/config.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["del"])) {
    deleteNews(con(), $_POST["del"]);
    header("location: ../?error=delnews&id=".$_POST["del"]);
    exit();
}

if (!isset($_POST["news"])) {
    header("location: ../?error=notfromsubmit");
    exit();
}

if (empty($_POST["news"]) || empty($_POST["power"])) {
    header("location: ../admin.php?error=emptyf&page=news");
    exit();
}

$news = nl2br($_POST["news"]);

if (strlen($news) > 2000) {
    header("location: ../admin.php?error=toolong&page=news");
    exit();
}

$power = $_POST["power"];

if ($power > 128) {
    $power = 128;
}

editNews(con(), $news, $power);