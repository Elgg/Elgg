<?php


//    ELGG join-with-no-invite page

// Run includes
define("context", "external");
require_once (dirname(dirname(__FILE__)) . "/../includes.php");

run("invite:init");
templates_page_setup();
if (!array_key_exists('invitation_success', $messages)) {
  $title= sprintf(__gettext("Join %s"), sitename);

  $body= run("content:invite:join");
  $body .= run("join:no_invite");

} else {
  $title= sprintf(__gettext("Welcome to %s"), sitename);

  if (array_key_exists("invite:register:welcome:success", $function)) {
    $body = run("invite:register:welcome:success");
  } else {
    $body = run("invite:register:default:welcome:success");
  }
}

templates_page_output($title, $body);

?>
