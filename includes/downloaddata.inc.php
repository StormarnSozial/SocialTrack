<?php

session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("location: ../");
    exit();
}

if (isset($_POST["datac"]) || empty($_POST["datac"])) {
  $usrName = $_SESSION["username"];

  header('Content-type: application/vnd-ms-excel');
  $team = $_POST["team"];
  if ($team == "null") {
    $teamName = "alle";
  } else {
    $teamName = teamData($con, $team)["name"];
  }
  $filename=$usrName."-".$teamName.".xls";
  header("Content-Disposition:attachment;filename=\"$filename\"");
  ?>
  <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Name</th>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Team</th>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Dauer</th>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Datum</th>
      </tr>
    </thead>
    <tbody>
      <?php
      datas($con, $_SESSION["username"], $team);
      ?>
      <tr></tr>
      <tr>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;">Gesamt: </td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;"><?php echo(getAllLessonsCount($con, $usrName, $_POST["team"])." lessons"); ?></td>
          <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      </tr>
    </tbody>
  </table>
  <?php
} else {

  $team = $_POST["team"];

  header('Content-type: application/vnd-ms-excel');
  $filename=$team.".xls";
  header("Content-Disposition:attachment;filename=\"$filename\"");?>
  <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">User</th>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Name</th>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Duration</th>
        <th style="padding-left: 10px; padding-right: 10px; border: 2px solid black;">Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
      teamDatas($con, $team);
      ?>
    </tbody>
  </table><?php

}
