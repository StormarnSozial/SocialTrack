<?php
  include_once "header.php";
  if (empty($_SESSION["username"])) {
    header("location: ./");
    exit();
  }
  if (getUserPower($con, $_SESSION["username"]) < 40) {
    header("location: ./?error=noperm");
    exit();
  }
?>
    <script type="text/javascript">
        document.getElementById("datas").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
    </script>
    <div class="main">
    <form action="datacenter.php" method="post">
        <button type="submit" name="submit">Filtern</button>
        <?php 
            if (getUserPower($con, $_SESSION["username"]) < 50) {
                teamsListLeader($con); 
            } else {
                teamsList($con);
            }
        ?>
    </form>
    <?php 
        if (isset($_POST["team"]) && $_POST["team"] != "null") {
            $teamName = teamData($con, $_POST["team"])["name"];
            echo("<p>Gefiltert für team: '".$teamName."'</p>");
        }
    ?>
    <table class="profile" style="float: none; margin: 30px auto; font-size: larger; align-items: center;">
        <thead>
            <tr>
            <th style="padding-left: 10px; padding-right: 10px;">Benutzer</th>
            <th style="padding-left: 10px; padding-right: 10px;">ID</th>
            <th style="padding-left: 10px; padding-right: 10px;">Name</th>
            <th style="padding-left: 10px; padding-right: 10px;">Team</th>
            <th style="padding-left: 10px; padding-right: 10px;">Dauer</th>
            <th style="padding-left: 10px; padding-right: 10px;">Datum</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $team = "null";
            if (!empty($_POST["team"])) {
            $team = $_POST["team"];
            }
            teamDatas($con, $team);
        ?>
        </tbody>
    </table>
    </div>
    <div class="main">
      <form action="includes/datamanager.inc.php" method="post">
        <input type="hidden" name="datac" value="hey ich bin in der database">
        <input type="number" name="id" placeholder="ID..."><br>
        <input type="text" name="name" placeholder="Name..."><br>
        <?php teamsList($con); ?>
        <input type="number" name="lessons" placeholder="Stunden..."><br>
        <input type="datetime-local" name="date" placeholder="Datum..." style="width: 250px;"><br>
        <button type="submit" name="edit">Edit</button><br><br>
        <button type="submit" name="del">Delete</button>
      </form>
      <?php
            if (isset($_GET["error"])) {
              if ($_GET["error"] == "error") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
              } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in every field!";
              } elseif ($_GET["error"] == "dataedited") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Daten bearbeitet!</p>";
                echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' wurde bearbeitet!</p>";
              } elseif ($_GET["error"] == "eerror") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal Error! Retry later!";
              } elseif ($_GET["error"] == "eemptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtiges Feld war leer!";
              } elseif ($_GET["error"] == "deldata") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Deleted data!</p>";
                echo "<p style='color: lime; border: solid green; max-width: 400px; text-align: center; margin: 10px auto; border-radius: 7px; margin-bottom: 10px;'>Event '".$_GET["name"]."' wurde aus der Datenbank gelöscht!</p>";
              } elseif ($_GET["error"] == "invalid") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Der Name darf folgende Zeichen nicht enthalten: <br>'<' and '>'!</p>";
              }
            }
      ?>
    </div>