<?php
	$user_id = $parameter[0];
	$persons = db_query("SELECT ident FROM users WHERE user_type='person'");
	
	$query = "SELECT friend FROM friends WHERE owner=" . $_SESSION["userid"] . " " .
			"AND friend IN (";
	$num_persons = count($persons);
	
	for($i=0;$i<$num_persons;$i++){
		if($i!=0)
			$query .= "," . $persons[$i]->ident;
		else
			$query .= $persons[$i]->ident;
	}
	
	$query .= ")";
		
	$run_result = db_query($query);
?>