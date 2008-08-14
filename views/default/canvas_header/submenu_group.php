<?php

	if (isset($vars['group_name'])) {
		$groupname = $vars['group_name'];
	} else {
		$groupname = "main";
	}
	if (isset($vars['submenu'])) {
		
		echo "<div class=\"submenu_group\"><div class=\"submenu_group_{$groupname}\">{$vars['submenu']}</div></div>";
		
	}

?>