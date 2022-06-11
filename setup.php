<?php
require_once 'config/config.inc.php';
require_once 'includes/functions.inc.php';

if (isSetupt(con())) {
    header("location: ./?error=issetedup");
    exit();
}

?>

<html lang="de">
    <header>
        <title>Sebsurf Setup</title>
        <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    </header>
    <body>
        <div class="main">
            <h1>Setup</h1><br><br>
            <h2>Administrator Account erstellen:</h2>
            <form action="includes/setup.inc.php" method="post">
                <input type="text" name="aname" placeholder="Name..." style="height: 60px;">
                <input type="password" name="apw" placeholder="Passwort..." style="height: 60px;"><br>
                <button type="submit" name="setup">Setup durchführen!</button>
            </form><br>
      <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!</p>";
            } elseif ($_GET["error"] == "1") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>MySQL Fehler!</p>";
            } elseif ($_GET["error"] == "toolong") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Accountnamen dürfen nicht länger als 64 Zeichen sein!</p>";
            } elseif ($_GET["error"] == "nameinvalid") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dieser Accountname ist nicht gültig!</p>";
            }
        }
      ?>
      </div>
  </body>
</html>