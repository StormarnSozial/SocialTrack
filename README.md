# SebSurf
A service to save social activities in one spot!

# Requirements

 - Host with webservice (Apache, Nginx, etc.)
 - MySQL Server

# Installation

 1. Make sure that you´ve installed git
 2. type command in Terminal/Console "git clone https://github.com/SebiGHG/sebsurf.git"
 3. create a file in the folder "includes" named "dbh.inc.php"
 4. edit that file with the following code and fill the quotes with your informations

```
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
```
