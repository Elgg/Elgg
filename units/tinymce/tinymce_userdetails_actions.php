<?php

	// Save the user's editor choice

	if (logged_on && isset($_REQUEST['action']) && run("permissions:check",
		array("userdetails:change",((int) $_REQUEST['id'])))) {

		if (isset($_REQUEST['visualeditor']) && ($_REQUEST['visualeditor'] == "yes" || $_REQUEST['visualeditor'] == "no")) {

			// Get the current value, will also create an initial entry if not yet set
			$current = run('userdetails:editor', (int) $_REQUEST['id']);
			$value   = $_REQUEST['visualeditor'];
			$id	  = (int) $_REQUEST['id'];

			if ($current == $value) {
				$messages[] .= gettext("Your editor preferences have been saved");
			} else {
				db_query("update user_flags set value = '$value' where flag = 'visualeditor' and user_id = $id");
				$messages[] .= gettext("Your editor preferences have been changed");
			}
		}
	}

?>
