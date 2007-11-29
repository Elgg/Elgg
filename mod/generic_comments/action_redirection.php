<?php

//    ELGG comments perform-action-then-redirect page

// Load Elgg framework
@require_once("../../includes.php");

run("comments:init");

global $messages;

if (isset($messages) && sizeof($messages) > 0) {
    $_SESSION['messages'] = $messages;
}

if (defined('redirect_url')) {
    header("Location: " . redirect_url);
} else {
    header("Location: " . url);
}

?>