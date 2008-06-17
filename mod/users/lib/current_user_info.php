<?php

    if (logged_on) {
        $run_result .= run("users:infobox", array("You",array($_SESSION['userid'])));
    }

?>