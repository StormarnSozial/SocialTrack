<?php
  include_once 'header.php';
  if (!isset($_SESSION["username"])) {
    header("location: log-in.php");
    exit();
  }
 ?>
  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "0") {
      echo "<p style='color: lime; border: solid green; max-width: 500px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du hast dich erfolgreich eingelogt als '".$_SESSION['username']."'!</p>";
    }
    elseif ($_GET["error"] == "1") {
      echo "<p style='color: red; border: solid red; max-width: 450px; text-align: center; margin: 10px auto; border-radius: 7px;'>Internal error: Something went wrong!";
    }
    elseif ($_GET["error"] == "noperm") {
      echo "<p style='color: red; border: solid red; max-width: 300px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dir ist es nicht erlaubt diese Seite zu betreten!";
    }
    elseif ($_GET["error"] == "issetedup") {
      echo "<p style='color: lime; border: solid green; max-width: 300px; text-align: center; margin: 10px auto; border-radius: 7px;'>Die Datenbank ist bereits vollständig eingerichtet!";
    }
  }
  ?>
  <script type="text/javascript">
    document.getElementById("home").setAttribute("style", "border: solid white; border-radius: 7px; padding: 3px;")
  </script>
  <div class="main" style="width: fit-content;">
    <p>Moin <?php echo($_SESSION["nick"]); ?>!</p>
  </div>

  <?php 
  if (currentNewsData(con()) !== false) {?>
    <h1 style="font-size: 3rem; margin-top: 30px;">Neues:</h1>
    <?php
    homeNews(con());
  }

  /*
  
  if (getUserPower(con(), $_SESSION["username"]) > 100) {
  ?>
  <div class="main">
    <h2>Updates:</h2>
    <?php versions(con()); ?>
  </div>
  <?php
  }
  */
  ?>
