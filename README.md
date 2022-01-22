# SebSurf
A service to save social activities in one spot!

# Anforderugen

 - Host mit einem webservice (Apache, Nginx, etc.)
 - Einen MySQL Server

# Installation

 1. Stelle sicher, dass du git installiert hast
 2. führe diesen Command in der Konsole, des Hostservers aus "git clone https://github.com/ItsSebis/sebsurf.git"
 3. gehe in den soeben erstellten order
 4. erstelle eine Datei im Unterordner "includes" namens "dbh.inc.php"
 5. fülle diese Datei mit dem unten stehenden Text und fülle die Felder mit deinen Informationen

```
<?php

# Es wird empfolen den MySQL Server auf dem selben Server zu hosten, wie die Weboberfläche!
# Dies führt zu eindeutig schnelleren Wartezeiten!

$serverName = "IP of your MySQL Server";

$dbUsr = "Database username";
$dbPw = "Database password";

$dbName = "Database it self";

$con = mysqli_connect($serverName, $dbUsr, $dbPw, $dbName);

if (!$con) {
  header("location: noconnection.htm");
  exit();
}
```

**Danke, dass du SebSurf benutzt!**
