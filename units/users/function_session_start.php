<?php
  // Start the session
  session_name(user_session_name);
  session_start();

  // Check to see if authorization is needed (check cookie)
  $logged_in = authenticate_account();

  // Set logged-in status in stone
  define('logged_on', $logged_in);
		
  // If we're not logged in, set the user ID to -1
  if (!logged_on) {
    $_SESSION['userid'] = -1;
  }
?>
