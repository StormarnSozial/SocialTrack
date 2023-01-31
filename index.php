<?php
  require_once "includes/require.php";
  require_once 'header.php';
  if (!isset($_SESSION["username"])) {
    echo "<script>location.href = './log-in.php'</script>";
  }
 ?>
  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "loggedIn") {
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
<div class="main" style="min-width: fit-content; height: min-content">
    <p>Moin <?php echo($_SESSION["nick"]); ?>!</p>
    <img alt="sozialpass cover" src="img/sozialpass.jpeg">
</div>
