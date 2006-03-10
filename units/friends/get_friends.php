<?php

	// Gets all the friends of a particular user, as specified in $parameter[0],
	// and return it in a data structure with the idents of all the users
	
		$ident = (int) $parameter[0];
		/*
		if (!isset($_SESSION['friends_cache'][$ident]) || (time() - $_SESSION['friends_cache'][$ident]->created > 120)) {
			$_SESSION['friends_cache'][$ident]->created = time();
			$_SESSION['friends_cache'][$ident]->data = db_query("select friends.friend as user_id,
										users.name from friends
										left join users on users.ident = friends.friend
										where friends.owner = $ident");
		}
		$run_result = $_SESSION['friends_cache'][$ident]->data;*/
		
		$run_result = db_query("select friends.friend as user_id,
										users.name from friends
										join users on users.ident = friends.friend
										where friends.owner = $ident");
				
?>