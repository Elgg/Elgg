<?php
  /** 
   * Function to log off 
   */

  // Kill entire session (well done)
  session_unset();
  session_destroy();
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-84600, '/');
  }

  // Remove the any AUTH_COOKIE persistent logins
  setcookie(AUTH_COOKIE, '', time()-84600, '/');
	
  // Set headers to forward to main URL
  header("Location: " . url . "\n");

?>
