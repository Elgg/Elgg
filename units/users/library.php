<?php
  /**
   * User-related functions
   */
	
  // Get current access level
  function accesslevel($owner = -1) {
    $currentaccess = 0;

    // For now, there are three access levels: 0 (logged out), 1 (logged in) and 1000 (me)
    if (logged_on == 1) {
      $currentaccess++;
    }
			
    if ($_SESSION['userid'] == $owner) {
      $currentaccess += 1000;
    }
			
    return $currentaccess;
  }
	
  // Protect users to a certain access level
  function protect($level, $owner = -1) {
    if (accesslevel($owner) < $level) {
      run("access_denied");

      // run("display:bottomofpage");
      exit();
    }
  }

  // Authentication Function
  // Returns true or false
  function authenticate_account($l='', $p='') {

    // If login and password passed, check DB (standard login)
    if($l && $p) {
      /*** TODO: Create Proper Abstraction Interface - don't use file binding -- ugh ***/
      return login_database($l, $p);
    }

    // Already logged in, we're done
    if($_SESSION['userid'] > 0) {
	    if (!run("users:flags:get", array("banned", $_SESSION['userid']))) {
	    	return 1;
    	}
    }

    // Check to see if there's a persistent cookie
    if($ticket = md5($_COOKIE[AUTH_COOKIE])) {
      $sql = "SELECT ident, code FROM users WHERE code = '$ticket'";
      $result = db_query($sql);
      if($row = $result[0]) {
        if($ticket == $row->code) {
          /*** TODO: Create Proper Abstraction Interface - don't use file binding -- ugh ***/
          if (!run("users:flags:get", array("banned",$row->ident))) {
          	init_session_database($row->ident);
          	return 1;
      	  } else {
	      	  global $messages;
	      	  $messages[] = gettext("You have been banned from the system!");
      	  }
        }
      }
    }

    // Everything failed
    return 0;
  }

  // Specific Login Call
  function login_database($l, $p) {
    $sql = "SELECT ident
            FROM users
            WHERE username = '$l'
            AND password = '$p'
            AND active = 'yes'
            AND user_type = 'person'";
    $result = db_query($sql);
    if($row = $result[0]) {
      $ok = init_session_database($row->ident);
      if (run("users:flags:get", array("banned", $row->ident))) {
	      $ok = false;
	      $row = false;
	      global $messages;
	      $messages[] = gettext("You have been banned from the system!");
      }
    }

    // Set Persistent Cookie
    if($_POST['remember']) {
      remember_login($row->ident);
    }

    return $ok;
  }

  // Initialize and Fill Session
  // * Note: this is abtracted from login_database so cookie lookups can
  //         share the init_session code
  function init_session_database($id) {
    if(!$id) return 0;

    $sql = "SELECT * 
            FROM users 
            WHERE ident = $id
            AND active = 'yes'
            AND user_type = 'person'";
    $result = db_query($sql);

    if($row = $result[0]) {
      $_SESSION['userid'] = (int) $row->ident;
      $_SESSION['username'] = stripslashes($row->username);
      $_SESSION['name'] = stripslashes($row->name);
      $_SESSION['email'] = stripslashes($row->email);
      $iconid = (int) $row->icon;
      if ($iconid == -1) {
        $_SESSION['icon'] = "default.png";
      } else {
        $icon = db_query("select filename from icons where ident = $iconid");
        $_SESSION['icon'] = $icon[0]->filename;
      }
      $_SESSION['icon_quota'] = (int) $row->icon_quota;

      return 1;
    } else {
      return 0;
    }
  }

  function remember_login($id) {
    if(!$id) return 0;

    // Double MD5
    $ticket    = md5(SECRET_SALT . $id . time());
    $md5ticket = md5($ticket);

    // Update MD5 of authticket
    $sql = "UPDATE users SET code = '$md5ticket' WHERE ident = $id";
    db_query($sql);

    setcookie(AUTH_COOKIE, $ticket, time()+AUTH_COOKIE_LENGTH, '/');

    return 1;
  }


?>
