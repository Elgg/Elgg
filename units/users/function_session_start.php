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
  } else {
	  // If we are logged in..
	  // Update the 'last action' time counter to now for the current user
	  db_query("update users set last_action = " . time() . " where ident = " . $_SESSION['userid']);
  }
  
?>
