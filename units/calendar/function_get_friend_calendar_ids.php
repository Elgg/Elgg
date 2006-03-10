<?php
	$user_ids = $parameter[0];
	
	$run_result = db_query("SELECT ident FROM calendar WHERE owner IN (" . implode(",", $user_ids) .")");
?>
