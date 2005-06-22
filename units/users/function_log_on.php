<?php
  /**
   * Login Functionality
   */

   $l = addslashes($_POST['username']);
   $p =  md5($_POST['password']);

   if($l && $p) {
    $ok = authenticate_account($l, $p);
    if($ok) {

     $messages[] = AUTH_MSG_OK;
     define('redirect_url',url . "home.php");
    } else {
      $messages[] = AUTH_MSG_BADLOGIN;
    }
  } else {
    $messages[] = AUTH_MSG_MISSING;
  }
?>
