<?php

	require("includes.php");

	db_query("delete from friends");
	db_query("delete from groups");
	db_query("delete from group_membership");
	
	$people = db_query("select * from users");
	
	foreach ($people as $person) {

		if ($person->ident != 1 && $person->ident != 2 && $person->ident != 8) {
			db_query("insert into friends set owner = " . $person->ident . ", friend = 1");
			db_query("insert into friends set owner = " . $person->ident . ", friend = 2");
			db_query("insert into friends set owner = " . $person->ident . ", friend = 8");
		}	

	}

?>