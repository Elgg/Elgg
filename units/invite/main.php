<?php

	// Invite a friend
	
	// Actions
		$function['invite:init'][] = path . "units/invite/invite_actions.php";
	
	// Introductory text
		$function['content:invite:invite'][] = path . "content/invite/invite.php";
		
	// Allow user to invite a friend
		$function['invite:invite'][] = path . "units/invite/invite.php";
		$function['invite:join'][] = path . "units/invite/invite_join.php";
		$function['join:no_invite'][] = path . "units/invite/join_noinvite.php";

	// Menu bar
		$function['menu:main'][] = path . "units/invite/menu_main.php";
		
?>