<?php

	$ident = (int) $parameter;
	
	$result = db_query("select name from users where ident = $ident");
	$run_result .= stripslashes($result[0]->name);

?>