<?php

# Es wird empfolen den MySQL Server auf dem selben Server zu hosten, wie die Weboberfläche!
# Dies führt zu eindeutig schnelleren Wartezeiten!

$serverName = "37.114.53.18";

$dbUsr = "seburf";
$dbPw = "C75ahisALijI";

$dbName = "sebsurf";

$con = mysqli_connect($serverName, $dbUsr, $dbPw, $dbName);

if (!$con) {
  header("location: noconnection.htm");
  exit();
  die("Connection failed: " . mysqli_connect_error());
}