<?php

	$ident = (int) $parameter[0];
	
	$result = db_query("select name from users where ident = '$ident'");
	echo stripslashes($result[0]->name);

?>