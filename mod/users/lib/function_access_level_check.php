<?php

// Given the access level of a particular object, returns TRUE if the current user
// can access it, and FALSE if they can't

if (isset($parameter) && $parameter != "") {
    if (isloggedin() && isadmin($_SESSION['userid'])) {
        $run_result = true;
    }
    elseif ($parameter == "PUBLIC") {
        $run_result = true;
    } else if ($parameter == "LOGGED_IN" && isset($_SESSION['userid']) && $_SESSION['userid'] != "" && $_SESSION['userid'] != -1) {
        $run_result = true;
    } else if (substr_count($parameter, "user") > 0 && isset($_SESSION['userid'])) {
        $usernum = substr($parameter, 4, 15);
        if ($usernum == $_SESSION['userid']) {
            $run_result = true;
        }
    }
} else {
    $run_result = false;
}

?>