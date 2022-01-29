<?php

session_start();
require_once '../config/dbh.inc.php';
require_once 'functions.inc.php';

if (empty($_SESSION["username"])) {
    header("location: ../");
    exit();
}

$usrName = $_SESSION["username"];

header('Content-type: application/vnd-ms-excel');
$team = $_POST["team"];
if ($team == "null") {
  $teamName = "alle";
} else {
  $teamName = teamData(con(), $team)["name"];
}
$filename=$usrName."-".$teamName.".xls";
header("Content-Disposition:attachment;filename=\"$filename\"");
?>
<table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
  <thead>
    <tr>
      <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Name</th>
      <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Team</th>
      <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Bereich</th>
      <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Dauer</th>
      <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Datum</th>
      <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Signiert</th>
    </tr>
  </thead>
  <tbody>
    <?php
    dataDownload(con(), $_SESSION["username"], $team);
    ?>
    <tr></tr>
    <tr>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;">Gesamt: </td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;"><?php echo(getAllLessonsCount(con(), $usrName, $_POST["team"])." Stunden"); ?></td>
        <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
    </tr>
  </tbody>
</table>