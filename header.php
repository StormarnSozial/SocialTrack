<?php
if (!isSetupt(con())) {
    header("location: setup.php");
    exit();
}

elseif (!isset($_SESSION["username"]) && isset($_COOKIE["remember"])) {
    list($selector, $authenticator) = explode(':', $_COOKIE['remember']);
    $row = tokenData(con(), $selector);
    if ($row !== false) {
        $user = userDataById(con(), $row["userid"]);

        // 1209600 s => 2 weeks
        if (hash_equals($row["token"], hash("sha256", base64_decode($authenticator))) && $user["disabled"] !== 1) {
            $_SESSION["userid"] = $user["id"];
            $_SESSION["username"] = $user["account"];
            if (empty($user["nick"])) {
                $_SESSION["nick"] = $user["fullname"];
            } else {
                $_SESSION["nick"] = $user["nick"];
            }
            $_SESSION["adminentry"] = false;
            updateUserLessons(con(), $user["account"]);
            updateToken(con(), $selector); // refresh token for this device
        } else {
            unset($_COOKIE["remember"]);
            setcookie("remember", null, -1, "/");
        }
    } else {
        unset($_COOKIE["remember"]);
        setcookie("remember", null, -1, "/");
    }


}

elseif (basename(__DIR__) !== "beta" && isset($_SESSION["username"]) && userData(con(), $_SESSION["username"])["beta"] && $_SERVER["HTTP_HOST"] == "sebsurf.stormarnschueler.de") {
    header("location: ./beta");
}

# Get version
$version = "1.3.1";

if (basename(__DIR__) == "beta") {
    $version .= " β";
}

?>
<?php
if (!isset($_GET["ajax"])) {
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>SebSurf | SocialClient</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/logic.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&family=Roboto+Mono:wght@300&display=swap"
          rel="stylesheet">
    <link rel="icon" href="img/icon-title.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
          integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<script src="js/load.js"></script>
<!--<script src="js/ajax.js"></script>-->
<div id="loadable_content">
    <?php
    }
    ?>
    <!--  Old Navi
      <div class="nav">
      <img src="img/logo_stormarnschule.png" alt="logo" class="logo">
      <div class="links">
        <?php
    /*        if (isset($_SESSION["username"])) {
              echo '<a href="./" id="home" class="navihome">Home</a><br><br>';
              echo '<a href="profile.php" id="profile" class="navi">Events</a><br><br>';
              if (isTeamLeader(con(), $_SESSION["username"]) || getUserPower(con(), $_SESSION["username"]) >= 40) {
                echo '<a href="teams.php" id="teams" class="navi">Teams</a><br><br>';
                echo '<a href="datacenter.php" id="datas" class="navi">Datenbank</a><br><br>';
              }
              if (isAdmin(con(), $_SESSION["username"])) {
                $count = "";
                if (getAllRequestsCount(con()) != 0) {$count = " <span style='color: black; border: solid red; border-radius: 10px; background-color:
                  red'>".getAllRequestsCount(con())."</span>";}
                echo '<a href="admin.php" id="admin" class="navi">Verwaltung'.$count.'</a><br><br>';
              }
              $count = "";
              if (getAllNotifyCount(con(), $_SESSION["username"]) != 0) {$count = " <span style='color: black; border: solid red; border-radius: 10px; background-color:
                red'>".getAllNotifyCount(con(), $_SESSION["username"])."</span>";}
              echo '<a href="notifications.php" id="notifies" class="navinotifies">Messenger'.$count.'</a><br><br>';
              echo '<a href="settings.php" id="settings" class="naviprofile">Profil</a><br><br>';
              echo '<a href="includes/logouts.inc.php" class="navilogout" data-enableajax="off">Logout</a><br><br>';
            }
            else {
              echo '<a href="log-in.php" id="login" class="navilogin" data-enableajax="off">Log in</a><br><br>';
            }
            echo '<br><a href="support.php" id="sup" class="navisupport">Support</a><br><br>';
            */ ?><br>
        <p style="font-size: smaller; font-weight: 100; color: grey; border-top: 1px solid grey; padding: 5px; width: fit-content; margin: 0 auto;"><?php /*echo($version); */ ?></p>
      </div>
    </div>-->
    <div class="navigation">
        <div class="logo_content">
            <div class="logo">
                <i class='bx bx-stats'></i>
                <div class="logo_name">SebSurf</div>
            </div>
            <i class='bx bx-menu' id="btn"></i>
        </div>

        <ul class="navigation_list">
            <!--            <li>
                            <i class='bx bx-search'></i>
                            <input type="text" placeholder="Suchen...">
                        </li>-->

            <?php
            if (isset($_SESSION["username"])) {
                $links = array(
                    array("name" => "Start", "link" => "./", "sym" => "bx bxs-home", "target" => "_self"),
                    array("name" => "Events", "link" => "profile.php", "sym" => "bx bx-calendar-event", "target" => "_self"),
                );
                if (isTeamLeader(con(), $_SESSION["username"]) || getUserPower(con(), $_SESSION["username"]) >= 40) {
                    $links[] = array("name" => "Teams", "link" => "teams.php", "sym" => "bx bx-user-check", "target" => "_self");
                    $links[] = array("name" => "Datenbank", "link" => "datacenter.php", "sym" => "bx bx-data", "target" => "_self");
                }
                if (getUserPower(con(), $_SESSION["username"]) >= 100) {
                    if (!isset($_SESSION["adminentry"]) || !$_SESSION["adminentry"]) {
                        $sym = "bx bx-lock-alt";
                    } else {
                        $sym = "bx bx-code-alt";
                    }
                    $count = "";
                    if (getAllRequestsCount(con()) != 0) {
                        $count = " <span style='color: black; border: solid red; border-radius: 10px; background-color:
                    red'>" . getAllRequestsCount(con()) . "</span>";
                    }
                    $links[] = array("name" => "Verwaltung" . $count, "link" => "admin.php", "sym" => $sym, "target" => "_self");
                }
                $count = "";
                if (getAllNotifyCount(con(), $_SESSION["username"]) != 0) {
                    $count = " <span style='color: black; border: solid red; border-radius: 10px; background-color:
                red'>" . getAllNotifyCount(con(), $_SESSION["username"]) . "</span>";
                }
                $links[] = array("name" => "Mitteilungen" . $count, "link" => "notifications.php", "sym" => "bx bx-news", "target" => "_self");
                $links[] = array("name" => "Profil", "link" => "settings.php", "sym" => "bx bx-user-circle", "target" => "_self");
            } else {
                $links = array(
                    array("name" => "Login", "link" => "log-in.php", "sym" => "bx bx-log-in", "target" => "_self"),
                );
            }
            $links[] = array("name" => "Support", "link" => "support.php", "sym" => "bx bx-support", "target" => "_self");


            foreach ($links as $link) {
                echo '
<li>
    <a id="'. $link["link"] .'" href="' . $link["link"] . '" target="' . $link["target"] . '">
        <i class="' . $link["sym"] . '"></i>
        <span class="links_name">' . $link["name"] . '</span>
    </a>
    <span class="tooltip">' . $link["name"] . '</span>
</li>
            ';
            }
            ?>

        </ul>
        <p style="font-size: smaller; font-weight: 100; color: grey; border-top: 1px solid grey; padding: 5px; width: 85%; margin: 20px auto 0;"><?php echo($version); ?></p>
        <?php
        if (isset($_SESSION["userid"])) {
            $data = userDataById(con(), $_SESSION["userid"]);
            $role = roleData(con(), $data["role"]);
            ?>
            <div class="profile_content">
                <div class="profile">
                    <div class="profile_details">
                        <img src="img/person.png" alt="">
                        <div class="name_job">
                            <p style="display: none" id="own_name"><?php echo(userData(con(), $_SESSION["username"])["fullname"]) ?></p>
                            <div class="name"><?php echo($_SESSION["nick"]) ?></div>
                            <div class="job"
                                 style="color: cyan"><?php echo($role["name"]) ?></div>
                        </div>
                    </div>
                    <a href="includes/logouts.inc.php"><i class="bx bx-log-out" id="log_out"></i></a>
                </div>
            </div>
            <?php
        } else { ?>
            <div class="profile_content">
                <div class="profile">
                    <div class="profile_details">
                        <img src="img/person.png" alt="">
                        <div class="name_job">
                            <div class="name">Nicht angemeldet</div>
                            <div class="job" style="color: grey">Keine Rolle</div>
                        </div>
                    </div>
                    <a href="log-in.php"><i class="bx bx-log-in" id="log_out"></i></a>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <script>
        // navigation function
        let btn = document.querySelector("#btn");
        let sidebar = document.querySelector(".navigation");

        if (window.innerWidth > window.innerHeight) {
            sidebar.classList.add("active")
        }

        btn.onclick = function () {
            sidebar.classList.toggle("active")
        }

        let url = window.location.pathname;
        let filename = url.substring(url.lastIndexOf('/')+1);
        if (filename === "") {
            filename = "./";
        }
        console.log(filename);
        document.getElementById(filename).classList.add('active');

    </script>
    <div class="content">
