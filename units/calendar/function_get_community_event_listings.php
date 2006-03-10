<?php
	
	$community_id = (int) $parameter[0];
	
	$query = "SELECT DISTINCT * FROM event " .
			 "WHERE access = 'community" . $community_id ."' ";
	
	$groups = db_query("SELECT group_id FROM group_membership " .
					   "WHERE user_id = " . $community_id);
	
	foreach($groups as $group){
		$query .= "OR access='group" . $group->group_id ."' ";
	}
	
	$query .= " ORDER BY date_start DESC";	 
			 
	$run_result = db_query($query);
	
?>
