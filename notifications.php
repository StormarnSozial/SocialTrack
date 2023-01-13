<?php
require_once "includes/require.php";
if (isset($_POST["send"])) {
    $group = $_POST["group"];
    $subject = $_POST["subject"];
    $text = $_POST["text"];

    if ($group == "null" || empty($subject) || empty($text)) {
        header("location: notifications.php?notify=create&error=emptyf");
        exit();
    }

    if (strlen($text) > 2000) {
        header("location: notifications.php?notify=create&error=toolong");
        exit();
    }

    foreach (grouperArray(con(), $group) as $user) {
        sendNotification(con(), userDataById(con(), $user)["account"], $_SESSION["username"], $subject." [".groupDataById(con(), $group)["name"]."]", $text);
    }
    header("location: notifications.php?usr=".groupDataById(con(), $group)["name"]."&error=send");
    exit();
} elseif (isset($_GET["exe"])) {
    if ($_GET["exe"] == "del") {
        delNotification(con(), $_GET["id"]);
    }
    header("location: notifications.php");
    exit();
}

if (empty($_SESSION["username"])) {
    header("location: ./log-in.php");
    exit();
}
include_once "header.php";
if (!isset($_GET["notify"])) {
?>
<div class="main">
    <h1>Mitteilungen:</h1><br>
    <?php
        notifyTable(con(), $_SESSION["username"]);
        #readAllNotifications(con(), $_SESSION["username"]);
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "send") {
                echo "<p style='color: lime; border: solid green; max-width: 500px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es wurde erfolgreich ein Nachricht an alle der Gruppe ".$_GET["usr"]." gesendet!</p>";
            }
        }
    ?>

    <script>
        function httpGet(theUrl) {
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
            xmlHttp.send( null );
            return xmlHttp.responseText;
        }

        let del_buttons = document.getElementsByClassName("del_btn");

        for (let del_btn of del_buttons) {
            del_btn.onclick = function () {
                httpGet("notifications.php?exe=del&id="+del_btn.getAttribute("value"))
                del_btn.parentElement.parentElement.remove();
                console.log("Deleted "+del_btn.getAttribute("value"))
            }
        }
    </script>

</div>
<?php
} else {
    $notifyid = $_GET["notify"];
    if (notifyData(con(), $notifyid)["usr"] != $_SESSION["username"]) {
        header("location: ./notifications.php");
        exit();
    }
    notification(con(), $notifyid);
}?>
