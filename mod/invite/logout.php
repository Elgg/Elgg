<?php

require_once(dirname(dirname(__FILE__))."/../includes.php");

$user->code = '';
$user->ident = $USER->ident;
if ($USER->ident) { //for some reason this can run with user id 0...?
    update_record('users',$user);
}

unset($USER);
unset($SESSION);
unset($_SESSION['USER']);
unset($_SESSION['SESSION']);
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['name']);
unset($_SESSION['email']);
unset($_SESSION['icon']);
unset($_SESSION['icon_quota']);

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-84600, $CFG->cookiepath);
}

// Remove the any AUTH_COOKIE persistent logins
setcookie(AUTH_COOKIE, '', time()-84600, $CFG->cookiepath);

session_destroy();

// Set headers to forward to main URL
header("Location: " . $CFG->wwwroot . "\n");


?>