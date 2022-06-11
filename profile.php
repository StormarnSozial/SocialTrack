<?php
include_once "header.php";
if (empty($_SESSION["username"])) {
  header("location: ./");
  exit();
}
?>
<script type="text/javascript">
  document.getElementById("profile").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
</script>
  <?php
  if ((!isset($_GET["data"]) || dataData(con(), $_GET["data"]) === false) && !isset($_GET["create"])) {
  ?>
  <div class="main">
  <h1>Deine Events</h1><br>
  <form action="includes/downloaddata.inc.php" target="_blank" method="post">
    <button type="submit" name="submit">Download</button>
    <?php teamsListMember(con(), $_SESSION["username"]); ?>
  </form>
  <form action="profile.php" method="post">
    <button type="submit" name="submit">Filter</button>
    <?php teamsListMember(con(), $_SESSION["username"]); ?>
  </form>
  <?php
    if (isset($_POST["team"]) && $_POST["team"] != "null") {
      $teamName = teamData(con(), $_POST["team"])["name"];
      echo("<p>Filtered by team: '".$teamName."'</p>");
  }
  ?>
  <form action="profile.php">
    <button type='submit' name='create'>Hinzufügen</button>
  </form><br>
  <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
    <thead>
      <tr>
        <th style="padding-left: 10px; padding-right: 10px;">Name</th>
        <th style="padding-left: 10px; padding-right: 10px;">Team</th>
        <th style="padding-left: 10px; padding-right: 10px;">Dauer</th>
        <th style="padding-left: 10px; padding-right: 10px;">Datum</th>
        <th style="padding-left: 10px; padding-right: 10px;">Signiert</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $team = "null";
      if (!empty($_POST["team"])) {
        $team = $_POST["team"];
      }
      datas(con(), $_SESSION["username"], $team, false);
    ?>
    <tr>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; color: #262626;"><p style="visibility: hidden;">moin :)</p></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
    </tr>
    <tr>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;">Signiert:</td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black; font-weight: bold;"><?php
      $team = "null";
      if (!empty($_POST["team"])) {
        $team = $_POST["team"];
      } 
      echo(getAllLessonsCount(con(), $_SESSION["username"], $team)." Stunden"); ?></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
      <td style="padding-left: 10px; padding-right: 10px; border: 2px solid black;"></td>
    </tr>
    </tbody>
  </table>
  <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "dataadded") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten hinzugefügt!</p>";
      } elseif ($_GET["error"] == "error") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
      } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in every field!";
      } elseif ($_GET["error"] == "dataedited") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten bearbeitet!</p>";
      } elseif ($_GET["error"] == "eerror") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
      } elseif ($_GET["error"] == "eemptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Important field was empty!";
      } elseif ($_GET["error"] == "deldata") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten vernichtet!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" .$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
      } elseif ($_GET["error"] == "invalid") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The name may not contain <br>'<' and '>'!</p>";
      }
    }
  ?>
  </div>
  
<!--  <div class="main">
    <h2>Notes:</h2><br>
    <form action="includes/usermanager.inc.php" method="post">
      <textarea style="background: none" name="note" cols="100" rows="30"><?php /*echo(userData(con(), $_SESSION["username"])["note"]); */?></textarea><br><br>
      <button type="submit" name="savenote">Sichern</button>
    </form>
    <?php
/*          if (isset($_GET["error"])) {
            if ($_GET["error"] == "notesaved") {
              echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Saved Changes!</p>";
            } elseif ($_GET["error"] == "inboundoutofcharacter") {
              echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Note cannot be longer than 2000 character!</p>";
            }
          }
    */?>
  </div>-->
  <?php
  } elseif (isset($_GET["data"])) {?>

<div class="main">
  <a href="profile.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
  <form action="includes/datamanager.inc.php" method="post">
    <input type="hidden" name="id" placeholder="ID..." value="<?php echo($_GET["data"]); ?>"><br>
    <?php
      $data = dataData(con(), $_GET["data"]);
      echo("<h1 style='font-size: 3rem;'>".$data["name"]."</h1>");
      echo '<h2>Aktuelle Werte:</h2>';
      echo '<p>Team: '.teamData(con(), $data["team"])["name"].'</p>';
      echo '<p>Stunden: '.$data["lessons"].'</p>';
      echo '<p>Datum: '.$data["edate"].'</p>';
    ?>
    <input type="text" name="name" placeholder="Name..." value="<?php echo(dataData(con(), $_GET["data"])["name"]); ?>"><br>
    <?php teamsListMember(con(), $_SESSION["username"]); ?>
    <input type="number" name="lessons" placeholder="Stunden..." value="<?php echo(dataData(con(), $_GET["data"])["lessons"]); ?>"><br>
    <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;" value="<?php echo(dataData(con(), $_GET["data"])["edate"]); ?>"><br>
    <?php #<button type="submit" name="add">Hinzufügen</button><br><br>?>
    <button type="submit" name="edit">Bearbeiten</button><br><br>
    <button type="submit" name="del">Löschen</button>
  </form>
  <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "dataadded") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten hinzugefügt!</p>";
      } elseif ($_GET["error"] == "error") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
      } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in every field!";
      } elseif ($_GET["error"] == "dataedited") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten bearbeitet!</p>";
      } elseif ($_GET["error"] == "eerror") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
      } elseif ($_GET["error"] == "eemptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Important field was empty!";
      } elseif ($_GET["error"] == "deldata") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Deleted data!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" .$_GET["name"]."' was deleted from the database!</p>";
      } elseif ($_GET["error"] == "invalid") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The name may not contain <br>'<' and '>'!</p>";
      }
    }
  ?>
</div>

    <?php
  } else {?>

    <div class="main">
      <a href="profile.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
      <form action="includes/datamanager.inc.php" method="post">
        <?php
          echo("<h1 style='font-size: 3rem; margin-top: 20px;'>Event Erstellen</h1>");
        ?>
        <input type="text" name="name" placeholder="Name..."><br>
        <?php teamsListMember(con(), $_SESSION["username"]); ?>
        <input type="number" name="lessons" placeholder="Stunden..."><br>
        <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;"><br>
        <button type="submit" name="add">Hinzufügen</button><br><br>
      </form>
  <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "dataadded") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten hinzugefügt!</p>";
      } elseif ($_GET["error"] == "error") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
      } elseif ($_GET["error"] == "emptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
      } elseif ($_GET["error"] == "dataedited") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten bearbeitet!</p>";
      } elseif ($_GET["error"] == "eerror") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
      } elseif ($_GET["error"] == "eemptyf") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Important field was empty!";
      } elseif ($_GET["error"] == "deldata") {
        echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Daten gelöscht!</p>";
        echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; border-radius: 7px; margin: 10px auto;'>Event '" .$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
      } elseif ($_GET["error"] == "invalid") {
        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>The name may not contain <br>'<' and '>'!</p>";
      }
    }
  ?>
    </div>

<?php
  }
  ?>
