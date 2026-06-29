<?php

require_once '../config/config.inc.php';
require_once 'functions.inc.php';

foreach (usersArray(con()) as $user) {
    updateUserLessons(con(), $user);
}