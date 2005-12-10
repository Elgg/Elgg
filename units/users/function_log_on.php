<?php
  /**
   * Login Functionality
   */

   $l = addslashes($_POST['username']);
   $p =  md5($_POST['password']);

   if($l && $p) {
    $ok = authenticate_account($l, $p);
    if($ok) {

     $messages[] = gettext("You have been logged on.");
     define('redirect_url',url . "home.php");
    } else {
      $messages[] = gettext("Unrecognised username or password. The system could not log you on, or you may not have activated your account.");
    }
  } else {
    $messages[] = gettext("Either the username or password were not specified. The system could not log you on.");
  }
?>
