<?php
	$user_id = (int) $parameter[0];
		
	$results = db_query("SELECT ident FROM calendar WHERE owner=". $user_id);
	$run_result = (int) $results[0]->ident;
?>
