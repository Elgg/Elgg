<?php
	
	$event_id = (int) $parameter[0];
	
	$result = db_query("SELECT * FROM event WHERE ident = " . $event_id);
	
	$run_result = $result;
?>
