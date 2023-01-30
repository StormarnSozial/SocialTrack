<?php
require_once "includes/require.php";
include_once 'header.php';
if (!isAdmin(con(), $_SESSION["username"])) {
    echo "Hier bist du falsch!";
    echo "<script>window.location.href = './'</script>";
    exit();
} else {
    $error = "";
    if (isset($_POST['submit_pass']) && $_POST['pass']) {
        $pass = $_POST["pass"];
        if (adminPwMatch(con(), $_SESSION["username"], $pass)) {
            $_SESSION["adminentry"] = true;
        } else {
            $error = "Wrong Password!";
        }
    }
}
?>
<?php

/**
 * @return void
 */
function errorService()
{
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "c") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Service erstellt!</p>";
        } elseif ($_GET["error"] == "toolong") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Servicenamen können nicht mehr als 64 Zeichen haben!";
        } elseif ($_GET["error"] == "emptyf") {
            echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
        } elseif ($_GET["error"] == "uniquename") {
            echo "<p style='color: red; border: solid red; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Es gibt bereits ein Tean mit dem Namen '" . $_GET['team'] . "'!</p>";
        } elseif ($_GET["error"] == "edited") {
            echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Service bearbeitet!</p>";
        }
    }
}

if ($_SESSION["adminentry"]) {
#####################################################################################################
?>
<div class="main" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-auto-rows: minmax(auto, auto); justify-content: space-evenly; justify-items: center; row-gap: 20px">
    <a id="ad-usr" style="font-size: 1.3rem;" href="admin.php?page=users">Benutzer</a>
    <a id="ad-rol" style="font-size: 1.3rem;" href="admin.php?page=roles">Rollen</a>
    <a id="ad-teams" style="font-size: 1.3rem;" href="admin.php?page=teams">Teams<?php if (getAllRequestsCount(con()) != 0) {echo(" <span style='color: black; border: solid red; border-radius: 15px;background-color: red'>" . getAllRequestsCount(con()) . "</span>");}?></a>
    <?php
    if (getUserPower(con(), $_SESSION["username"]) >= 128) {
        echo '<a id="ad-news" style="font-size: 1.3rem; grid-row: 3; grid-column: 2" href="admin.php?page=news">Neuigkeiten</a>';
    }
    ?>
    <a style="font-size: 1.3rem; grid-row: 2; grid-column: 2; text-decoration: underline red;" href="admin.php?error=loggedout">Admin Logout</a>
</div>
<?php
if (isset($_GET["sql"]) && getUserPower(con(), $_SESSION["username"]) > 127) {?>

    <div class="main">
        <form action="includes/customsql.inc.php" method="post">
            <input type="text" placeholder="Query" name="sqlAdmin">
            <button type="submit">Perform</button>
        </form>
    </div>

    <?php
}
if (!isset($_GET["page"]) || $_GET["page"] == "users") {
    if ((!isset($_GET["usr"]) || userData(con(), $_GET["usr"]) === false) && !isset($_GET["create"])) {
        ?>

        <script>
            let ovTab = document.getElementById("ad-usr");
            ovTab.setAttribute("style", ovTab.getAttribute("style")+"; text-decoration: underline;")
        </script>
        <div class='main'>

            <h1>Benutzer</h1><br>
            <form action="admin.php">
                <button type='submit' name='create' class="create"><i class='bx bx-plus' ></i> Hinzufügen</button>
            </form>
            <br>
            <div class="filters">
                <form action="admin.php">
                    <?php
                    if (isset($_GET["facc"])) {
                        $facc = $_GET["facc"];
                        $space = " ";
                    } else {
                        $facc = "";
                    }
                    echo '<input type="text" name="facc" placeholder="Accountname..." value="' . $facc . '">';
                    $role = $_GET["role"] ?? "null";
                    rolesList(con(), $role);
                    ?>
                    <button type='submit'>Filtern</button>
                </form>
            </div>
            <?php
            if (isset($_GET["facc"]) && isset($_GET["role"]) && ($_GET["role"] != "null" || roleData(con(), $_GET["role"]) !== false) && !(empty($_GET["facc"]) && empty($_GET["role"]))) {
                $facc = $_GET["facc"];
                $role = $_GET["role"];
                usersFiltered(con(), $facc, $role);
            } else {
                users(con());
            }
            ?>

            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "delusr") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto;border-radius: 7px;'>Benutzer gelöscht!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; margin: 10px auto;border-radius: 7px;'>" . $_GET["acc"] . "</p>";
                } elseif ($_GET["error"] == "systemroot") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Du kannst doch nicht root bearbeiten???</p>";
                }
            } ?>
        </div>
        <?php
    } elseif (isset($_GET["usr"])) { ?>

        <div class="main">
            <a href="admin.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem"><?php echo($_GET["usr"]); ?></h1><br>
            <form action="includes/usermanager.inc.php" method="post">
                <input type="hidden" name="user" placeholder="Account..." style="width: 500px;"
                       value="<?php echo($_GET["usr"]); ?>">
                <label>Accountname:</label><br>
                <input type="text" name="newacc" placeholder="Account..." style="width: 500px;"
                       value="<?php echo($_GET["usr"]); ?>"><br>
                <label>Nachnamen:</label><br>
                <input type="text" name="lastname" placeholder="Nachnamen..."
                       value="<?php echo(getName(con(), $_GET["usr"], true)['lastnames']); ?>"><br>
                <label>Vornamen:</label><br>
                <input type="text" name="firstname" placeholder="Vornamen..."
                       value="<?php echo(getName(con(), $_GET["usr"], true)['firstnames']); ?>"><br>
                <?php /*<input type="text" name="nick" placeholder="Nickname..." value="<?php echo(userData(con(), $_GET["usr"])["nick"]); ?>"><br>*/ ?>
                <input type="text" name="pw" placeholder="Passwort..." autocomplete="false"><br>
                <?php
                userActiveList(con(), $_GET["usr"]);
                if (getUserPower(con(), $_SESSION["username"]) >= 110) {
                    rolesListUser(con(), $_GET["usr"]);
                }
                ?>
                <input type="hidden" name="admin" value="1">
                <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeiten</button>
                <br>
                <button type="submit" name="del">Löschen</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "nopassword") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>No Password given!";
                } elseif ($_GET["error"] == "delself") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Lösch dich... nicht!</p>";
                } elseif ($_GET["error"] == "lesspower") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dafür hast du nicht genug Power!</p>";
                } elseif ($_GET["error"] == "delusr") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Benutzer gelöscht!</p>";
                } elseif ($_GET["error"] == "nouser") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dieser Benutzer existiert nicht!</p>";
                } elseif ($_GET["error"] == "userexists") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es existiert bereits ein Benutzer mit diesem Namen!</p>";
                } elseif ($_GET["error"] == "usercreated") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Benutzer erstellt!</p>";
                } elseif ($_GET["error"] == "usredited") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Benutzer bearbeitet!</p>";
                } elseif ($_GET["error"] == "charlimitreached") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Benutzernamen dürfen nicht länger als 64 Zeichen sein!</p>";
                } elseif ($_GET["error"] == "usrcreated") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Benutzer erstellt!</p>";
                }
            } ?>
        </div>

        <?php
    } else { ?>
        <div class="main">
            <a href="admin.php" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem">Benutzer Erstellen:</h1>
            <form action="includes/usermanager.inc.php" method="post">
                <input type="text" name="lastname" placeholder="Nachnamen..." style="width: 500px;"><br>
                <input type="text" name="firstname" placeholder="Vornamen..." style="width: 500px;"><br>
                <input type="hidden" name="disabled" value="0">
                <?php
                    rolesList(con(), 999, true);
                ?>
                <br>
                <button type="submit" name="create">Erstellen</button>
                <br><br>
                <?php
                if (isset($_GET["error"])) {
                    if ($_GET["error"] == "emptyf") {
                        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
                    } elseif ($_GET["error"] == "userexists") {
                        echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits einen User mit diesem Namen!</p>";
                    }
                } ?>
        </div>
        <?php
    }
} elseif ($_GET["page"] == "roles") {
    if ((!isset($_GET["role"]) || roleData(con(), $_GET["role"]) === false) && !isset($_GET["create"])) {
        ?>

        <script>
            let ovTab = document.getElementById("ad-rol");
            ovTab.setAttribute("style", ovTab.getAttribute("style")+"; text-decoration: underline;")
        </script>
        <div class='main'>
            <h1>Rollen</h1><br>
            <form action="admin.php">
                <input type="hidden" name="page" value="roles">
                <button type='submit' name='create' class="create"><i class='bx bx-plus' ></i> Hinzufügen</button>
            </form><br>
            <?php
            roles(con());
            ?>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "del") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle gelöscht!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "norole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es nicht!</p>";
                } elseif ($_GET["error"] == "rolecreated") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle erstellt!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "protrole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
                }
            } ?>
        </div>

        <?php
    } elseif (isset($_GET["role"])) { ?>
        <div class="main">
            <a href="admin.php?page=roles" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem"><?php echo(roleData(con(), $_GET["role"])["name"]); ?></h1>
            <form action="includes/rolemanager.inc.php" method="post">
                <input type="hidden" name="role" value="<?php echo($_GET["role"]); ?>">
                <input type="number" name="newh" placeholder="Höhe..." value="<?php echo($_GET["role"]); ?>"
                       style="width: 500px;"><br>
                <input type="text" name="name" placeholder="Name..."
                       value="<?php echo(roleData(con(), $_GET["role"])["name"]); ?>"><br>
                <input type="number" name="power" placeholder="Power..."
                       value="<?php echo(roleData(con(), $_GET["role"])["power"]); ?>"><br>
                <input type="number" name="flags" placeholder="Flags..."
                       value="<?php echo(roleData(con(), $_GET["role"])["flags"]); ?>" max="127" min="0"><br>
                <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeiten</button>
                <br>
                <button type="submit" name="del">Löschen</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "editedrole") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle bearbeitet!</p>";
                } elseif ($_GET["error"] == "del") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle gelöscht!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "norole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es nicht!</p>";
                } elseif ($_GET["error"] == "roleexists") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es bereits!</p>";
                } elseif ($_GET["error"] == "rolecreated") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle erstellt!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtige Felder waren leer!</p>";
                } elseif ($_GET["error"] == "lesspower") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dafür hast du nicht genug Power!</p>";
                } elseif ($_GET["error"] == "protrole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
                } elseif ($_GET["error"] == "toohighpower") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Die maximale Power ist 127!</p>";
                }
            } ?>
        </div>
        <?php
    } else { ?>
        <div class="main">
            <a href="admin.php?page=roles" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem">Rolle Erstellen:</h1>
            <form action="includes/rolemanager.inc.php" method="post">
                <input type="number" name="role" placeholder="Höhe..." style="width: 500px;"><br>
                <input type="text" name="name" placeholder="Name..."><br>
                <input type="number" name="power" placeholder="Power..."><br>
                <button type="submit" name="create">Erstellen</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "editedrole") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle bearbeitet!</p>";
                } elseif ($_GET["error"] == "del") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle gelöscht!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "norole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle gibt es nicht!</p>";
                } elseif ($_GET["error"] == "roleexists") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits eine Rolle mit dieser Höhe!</p>";
                } elseif ($_GET["error"] == "rolecreated") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Rolle erstellt!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtige Felder waren leer!</p>";
                } elseif ($_GET["error"] == "lesspower") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Dafür hast du nicht genug Power!</p>";
                } elseif ($_GET["error"] == "protrole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
                } elseif ($_GET["error"] == "toohighpower") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Die maximale Power ist 127!</p>";
                }
            } ?>
        </div>
        <?php
    }
} elseif ($_GET["page"] == "groups") {
    if ((!isset($_GET["gid"]) || groupDataById(con(), $_GET["gid"]) === false) && !isset($_GET["create"])) {
        ?>

        <script>
            let ovTab = document.getElementById("ad-group");
            ovTab.setAttribute("style", ovTab.getAttribute("style")+"; text-decoration: underline;")
        </script>
        <div class='main'>
            <h1>Gruppen</h1><br>
            <form action="admin.php">
                <input type="hidden" name="page" value="groups">
                <button type='submit' name='create'>Hinzufügen</button>
            </form>
            <?php
            groups(con());
            ?>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "del") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Gruppe gelöscht!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>" . $_GET["name"] . "</p>";
                } elseif ($_GET["error"] == "norole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Gruppe gibt es nicht!</p>";
                } elseif ($_GET["error"] == "protrole") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Diese Rolle ist vom System geschützt!</p>";
                }
            } ?>
        </div>

        <?php
    } elseif (isset($_GET["gid"])) { ?>
        <div class="main">
            <a href="admin.php?page=groups" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem"><?php echo(groupDataById(con(), $_GET["gid"])["name"]); ?></h1>
            <form action="includes/groupmanager.inc.php" method="post">
                <input type="hidden" name="group" value="<?php echo(groupDataById(con(), $_GET["gid"])["account"]); ?>">
                <input type="text" name="newacc" placeholder="Account..."
                       value="<?php echo(groupDataById(con(), $_GET["gid"])["account"]); ?>" style="width: 500px;"><br>
                <input type="text" name="name" placeholder="Name..."
                       value="<?php echo(groupDataById(con(), $_GET["gid"])["name"]); ?>"><br>
                <button type="submit" name="edit" style="margin-bottom: 7px;">Bearbeiten</button>
                <br>
                <button type="submit" name="delgroup">Löschen</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "edited") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Gruppe bearbeitet!</p>";
                } elseif ($_GET["error"] == "created") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Gruppe erstellt!</p>";
                } elseif ($_GET["error"] == "addedgrouper") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Benutzer zur Gruppe hinzugefügt!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>" . getName(con(), $_GET["usr"]) . "</p>";
                } elseif ($_GET["error"] == "delgrouper") {
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Benutzer aus der Gruppe entfernt!</p>";
                    echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>" . getName(con(), $_GET["usr"]) . "</p>";
                }
            } ?>
        </div>
        <div class="main">
            <?php
            groupTable(con(), $_GET["gid"]);
            ?>
            <br>
            <form action="includes/groupmanager.inc.php" method="post">
                <input type="hidden" name="group" value="<?php echo($_GET["gid"]); ?>">
                <?php
                userListNotInGroup(con(), $_GET["gid"]);
                ?>
                <button type="submit" name="add" style="margin-bottom: 7px;">Hinzufügen</button>
                <br>
            </form>
        </div>
        <?php
    } else { ?>
        <div class="main">
            <a href="admin.php?page=groups" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
            <h1 style="margin-top: 20px; font-size: 3rem">Gruppe Erstellen:</h1>
            <form action="includes/groupmanager.inc.php" method="post">
                <input type="text" name="name" placeholder="Name..." style="width: 500px;"><br>
                <button type="submit" name="create">Erstellen</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "exists") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Es gibt bereits eine Rolle mit diesem Account namen!</p>";
                } elseif ($_GET["error"] == "emptyf") {
                    echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Wichtige Felder waren leer!</p>";
                }
            } ?>
        </div>
        <?php
    }
} elseif ($_GET["page"] == "news") {
    if (getUserPower(con(), $_SESSION["username"]) < 128) {
        exit();
    }
    ?>

    <script>
        let ovTab = document.getElementById("ad-news");
        ovTab.setAttribute("style", ovTab.getAttribute("style")+"; text-decoration: underline;")
    </script>
    <div class="main">
        <h1>Neuigkeiten</h1>
        <form action="includes/newsmanager.inc.php" method="post">
            <br><textarea name="news" id="news" placeholder="Sende den Benutzern Neuigkeiten und Informationen!"
                          cols="50" rows="20" style="color: #FFF; background: none;"></textarea><br><br>
            <input type="number" placeholder="Power benötigt zum sehen der News!" name="power" value="0" max="<?php echo(getUserPower(con(), $_SESSION["username"])) ?>"><br>
            <button type="submit" name="add" style="margin-bottom: 7px;">Veröffentlichen!</button>
            <br>
        </form>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "edited") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Veröffentlicht!</p>";
            } elseif ($_GET["error"] == "toolong") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Neuigkeiten dürfen nicht mehr als 2000 
          Zeichen haben!";
            } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Please fill in all fields!";
            } elseif ($_GET["error"] == "cleaned") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Cleaned News!</p>";
            }
        } ?>
    </div>

    <?php
}
elseif ($_GET["page"] == "teams") {
if (((!isset($_GET["team"]) || teamData(con(), $_GET["team"]) === false) && (!isset($_GET["service"]) || serviceData(con(), $_GET["service"]) === false)) && !isset($_GET["create"]) && !isset($_GET["createservice"])) {
    ?>

    <script>
        let ovTab = document.getElementById("ad-teams");
        ovTab.setAttribute("style", ovTab.getAttribute("style")+"; text-decoration: underline;")
    </script>
    <div class="main">
        <h1>Team Anfragen</h1>
        <?php
        allTeamRequests(con());
        ?>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "accepted") {
                echo "<br><p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 
            7px; margin: 10px auto;'>Team Erstelung genemigt! Name: '" . $_GET['team'] . "'!</p>";
            } elseif ($_GET["error"] == "denied") {
                echo "<br><p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 
            7px; margin: 10px auto;'>Team Erstelung abgelehnt! Name: '" . $_GET['team'] . "'!</p>";
            }
        } ?>
    </div>
    <div class="main">
        <h1>Teams</h1><br>
        <form action="admin.php">
            <input type="hidden" name="page" value="teams">
            <button type='submit' name='create'>Hinzufügen</button>
        </form>
        <?php
        teams(con());
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "del") {
                echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Team '" . $_GET["team"] . "' wurde gelöscht!";
            }
        }
        ?>
    </div>
    <div class="main">
        <h1>Dienstbereiche</h1><br>
        <form action="admin.php">
            <input type="hidden" name="page" value="teams">
            <button type='submit' name='createservice'>Hinzufügen</button>
        </form>
        <?php
        services(con());
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "delservice") {
                echo "<p style='color: lime; border: solid green; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Service '" . $_GET["service"] . "' wurde gelöscht!";
            }
        }
        ?>
    </div>

    <?php
} elseif (isset($_GET["team"])) {
    // Team settings
    ?>

    <div class="main">
        <a href="admin.php?page=teams" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
        <?php
        echo '<h1 style="font-size: 3rem; margin-top: 10px;">' . teamData(con(), $_GET["team"])["name"] . '</h1>'
        ?>
        <form action="includes/teammanager.inc.php" method="post">
            <input type="hidden" name="team" value="<?php echo($_GET["team"]); ?>">
            <input type="text" name="name" id="team" placeholder="Team Name..." style="width: 500px;"
                   value="<?php echo(teamData(con(), $_GET["team"])["name"]); ?>"><br>
            <?php
            servicesListTeam(con(), $_GET["team"]);
            ?>
            <button type="submit" name="edit">Bearbeiten</button>
            <br><br>
            <button type="submit" name="delete">Löschen</button>
        </form>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "c") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Team Erstellt '" . teamData(con(), $_GET['team'])["name"] . "'!</p>";
            } elseif ($_GET["error"] == "toolong") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Teamnamen können nicht mehr als 64 Zeichen haben!";
            } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
            } elseif ($_GET["error"] == "edited") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Team bearbeitet!</p>";
            }
        } ?>
    </div>

    <?php
} elseif (isset($_GET["create"])) {
    // Team creator
    ?>

    <div class="main">
        <a href="admin.php?page=teams" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
        <h1 style="margin-top: 20px; font-size: 3rem">Team Erstellen:</h1>
        <form action="includes/teammanager.inc.php" method="post">
            <input type="text" name="name" placeholder="Name..."><br>
            <?php
            servicesList(con());
            ?>
            <button type="submit" name="create">Erstellen</button>
            <br><br>
        </form>
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "c") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Team Erstellt '" . teamData(con(), $_GET['team'])["name"] . "'!</p>";
            } elseif ($_GET["error"] == "toolong") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Teamnamen können nicht mehr als 64 Zeichen haben!";
            } elseif ($_GET["error"] == "emptyf") {
                echo "<p style='color: red; border: solid red; max-width: 260px; text-align: center; margin: 10px auto; border-radius: 7px;'>Fülle bitte alle Felder!";
            } elseif ($_GET["error"] == "uniquename") {
                echo "<p style='color: lime; border: solid green; max-width: 360px; text-align: center; border-radius: 7px; margin: 10px auto;'>Es gibt bereits ein Tean mit dem Namen '" . $_GET['team'] . "'!</p>";
            }
        } ?>
    </div>
    <?php
}
elseif (isset($_GET["service"])) {
// Service settings
?>

<div class="main">
    <a href="admin.php?page=teams" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
    <?php
    echo '<h1 style="font-size: 3rem; margin-top: 10px;">' . serviceData(con(), $_GET["service"])["name"] . '</h1>'
    ?>
    <form action="includes/servicemanager.inc.php" method="post">
        <input type="hidden" name="service" value="<?php echo($_GET["service"]); ?>">
        <input type="text" name="name" placeholder="Bereich Name..." style="width: 500px;"
               value="<?php echo(serviceData(con(), $_GET["service"])["name"]); ?>"><br>
        <input type="number" name="index" placeholder="Bereich Index..."
               value="<?php echo(serviceData(con(), $_GET["service"])["index"]); ?>"><br>
        <button type="submit" name="edit">Bearbeiten</button>
        <br><br>
        <button type="submit" name="del">Löschen</button>
    </form>
    <?php

    errorService();
    echo "
    </div>";

    } elseif (isset($_GET["createservice"])) {
    // Service creator
    ?>

    <div class="main">
        <a href="admin.php?page=teams" style='border: solid white; padding: 2px; border-radius: 5px;'>← Zurück</a>
        <?php
        echo '<h1 style="font-size: 3rem; margin-top: 10px;">Dienstbereich Erstellen:</h1>';
        ?>
        <form action="includes/servicemanager.inc.php" method="post">
            <input type="text" name="name" placeholder="Bereich Name..."><br>
            <input type="number" name="index" placeholder="Bereich Index..."><br>
            <button type="submit" name="create">Erstellen</button>
            <br><br>
        </form>
        <?php
        errorService();
        echo "
    </div>";
        }
        }
        } else {
            #####################################################################################################
            ?>
            <form class="log-in" method="post" action="" id="login_form">
                <h1>Weise dich bitte erneut aus!</h1>
                <p style="color: red; font-weight: 900;">Du betrittst Administrative Seiten!</p>
                <input type="password" name="pass" placeholder="Enter Password!">
                <button type="submit" name="submit_pass">LOG IN</button>
                <p>
                    <?php echo "<br><p style='color: red;'>" . $error . "</p>"; ?>
                </p>
            </form>
            <?php
        }
        ?>

    </div>
</div>
