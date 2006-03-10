<?php

	// Session variable name
	define('user_session_name', 'elgguser');
	
	// Persistent login cookie DEFs
	define('AUTH_COOKIE', 'elggperm');
	define('AUTH_COOKIE_LENGTH', 31556926); // 1YR in seconds
	
	// Messages
	define('AUTH_MSG_OK', gettext("You have been logged on."));
	define('AUTH_MSG_BADLOGIN', gettext("Unrecognised username or password. The system could not log you on, or you may not have activated your account."));
	define('AUTH_MSG_MISSING', gettext("Either the username or password were not specified. The system could not log you on."));

?>
