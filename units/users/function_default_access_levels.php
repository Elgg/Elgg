<?php

    // Add some access levels
    
        $data['access'][] = array(__gettext("Private"), "user" . $_SESSION['userid']);
        $data['access'][] = array(__gettext("Public"),"PUBLIC");
        $data['access'][] = array(__gettext("Logged in users"),"LOGGED_IN");

?>