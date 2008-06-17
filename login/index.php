<?php

define("context","external");

require_once(dirname(dirname(__FILE__)).'/includes.php');
global $CFG;

$redirect_url = trim(optional_param('passthru_url'));
if (empty($redirect_url) || substr_count($redirect_url,$CFG->wwwroot) == 0) {
    $redirect_url = $CFG->wwwroot . "index.php";
}

if (substr_count($redirect_url,$CFG->wwwroot) == 0) {
    $redirect_url = substr($CFG->wwwroot,0,strlen($CFG->wwwroot) - 1) . $redirect_url;
}

$redirect_url = str_replace("@","",$redirect_url);

// if we're already logged in, redirect away again.
if (logged_on) {
    $messages[] = __gettext("You are already logged on.");
    define('redirect_url', $redirect_url);
    $_SESSION['messages'] = $messages;
    header("Location: " . redirect_url);
    exit;
}

$l = optional_param('username');
$p = optional_param('password');

if (!empty($l) && !empty($p)) {
    $ok = authenticate_account($l, $p);
    if ($ok) {
        $messages[] = __gettext("You have been logged on.");
        if (md5($p) == md5("password")) {
            $messages[] = __gettext("The password for this account is extremely insecure and represents a major security risk. You should change it immediately.");
        }

        // override with redirect_url in session
        if (isset($_SESSION['redirect_url'])) {
            define('redirect_url', $_SESSION['redirect_url']);
            unset($_SESSION['redirect_url']);
        } else {
            define('redirect_url', $redirect_url);
        }
        header_redirect(redirect_url);
    } else {
        $messages[] = __gettext("Unrecognised username or password. The system could not log you on, or you may not have activated your account.");
    }
} else if (!empty($l) || !empty($p)) { // if ONLY one was entered, make the error message.
    $messages[] = __gettext("Either the username or password were not specified. The system could not log you on.");
}

$body = __gettext('Please log in');
templates_page_setup();
// display the form.
templates_page_output($CFG->sitename, $body);

?>
