<?php

	// Get the user preferences for the editor

	// Userid
	$id = (int) $parameter;

	// Editor is enabeled by default
	$value = "yes";

	// Query result
	$result = db_query("select value from user_flags value where flag = 'visualeditor' and user_id = $id");

	// Process resultset
	if ($row = $result[0]) {
		// We have a result, the preference has been set earlier
		$value = $row->value;
	} else {
		// No result, store a default value
		db_query("insert into user_flags set flag='visualeditor', value='$value', user_id=$id");
	}

	$run_result = $value;
?>
