<?php

	global $profile_id;
	$profile_id = (int) $profile_id;
	
	global $name_cache;
	
	if (!isset($name_cache[$profile_id]) || (time() - $name_cache[$profile_id]->created > 60)) {
		
		$result = db_query("select name from users where ident = '$profile_id'");
		// echo stripslashes($result[0]->name);
	
		$name_cache[$profile_id]->created = time();
		$name_cache[$profile_id]->data = stripslashes($result[0]->name);
		
	}
	$run_result = $name_cache[$profile_id]->data;
	
?>