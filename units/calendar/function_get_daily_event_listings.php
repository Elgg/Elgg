<?php
	global $calendar_id;
	
	$selected_month = (int) $parameter[0];
	$selected_year = (int) $parameter[1];
	$selected_day = (int) $parameter[2];
	$context = $parameter[3];
	
	//get time at beginning of selected day
	$start_time = mktime(0, 0, 0, $selected_month, $selected_day, $selected_year);
	
	//get time at end of selected day
	$end_time = mktime(23, 59, 59, $selected_month, $selected_day, $selected_year);
	
	$results = array();
		
	switch($context){
		case "private":
		default:
			$results = db_query("SELECT * FROM event " . 
								"WHERE (date_start >= ". $start_time ." ".
								"AND date_start <= ". $end_time ." ".
								"OR date_end <= ". $end_time ." ".
								"AND date_end >=". $start_time ." " .
								"OR date_start <= ". $start_time ." " .
								"AND date_end >= ". $end_time .") " .
								"AND (access='user" . $_SESSION["userid"] . "' " .
								"OR access='PUBLIC' " .
								"OR (access='LOGGED_IN' " .
								"AND owner=" . $calendar_id . ")) " .
								"ORDER BY date_start");
		break;
		case "friends":
			$results = run("calendar:get_person_type_friends", array($_SESSION["userid"]));
			$cnt = count($results);
			$friends = array();
			for($i=0;$i<$cnt;$i++){
				$friends[$i] = $results[$i]->friend;
			}
			
			$friends = run("calendar:get_friend_calendar_ids", array($friends));
			
			
			if(count($friends)){
				$query = "SELECT * FROM event " .
						 "WHERE (date_start >= ". $start_time ." ".
						 "AND date_start <= ". $end_time ." ".
						 "OR date_end <= ". $end_time ." ".
						 "AND date_end >=". $start_time ." " .
						 "OR date_start <= ". $start_time ." " .
						 "AND date_end >= ". $end_time .") " .
						 "AND (";
						 
				//friends calendar ids...owners
				$cnt = count($friends);
				for($i=0;$i<$cnt;$i++){
					if($i!=0)
						$query .= "OR owner=" . $friends[$i]->ident ." ";
					else
						$query .= "owner=" . $friends[$i]->ident ." ";
				}
				
				//access levels
				$query .= ") AND ( access='LOGGED_IN' ";
				
				$owned_groups = db_query("SELECT ident FROM groups WHERE owner=" . $_SESSION["userid"]);
				$member_groups = db_query("SELECT group_id as ident FROM group_membership WHERE user_id=" . $_SESSION["userid"]);
				$group_access = array_merge($owned_groups, $member_groups);
				$num_groups = count($group_access); 
				
				for($i=0;$i<$num_groups;$i++){
					$query .= "OR access='group" . $group_access[$i]->ident ."' ";
				}
				
				
				$query .= ") " .
						  "ORDER BY title";
				
				$results = db_query($query);
			}
		
		break;
		case "communities":
			$valid_access = array();
			$community_ids = array();
			
			foreach($data["access"] as $access){
				
				if(substr($access[1], 0, strlen("community")) == "community"){
					$valid_access[] = "'" . $access[1] . "'";
					$community_ids[] = substr($access[1], strlen("community"), strlen($access[1]));
				}
			}
			
			$results = db_query("SELECT * FROM event " .
								"WHERE access IN (" . implode(",", $valid_access) . ") " .
								"AND (date_start >= ". $start_time ." ".
								"AND date_start <= ". $end_time ." ".
								"OR date_end <= ". $end_time ." ".
								"AND date_end >=". $start_time." " .
								"OR date_start <= ". $start_time ." " .
								"AND date_end >= ". $end_time .") " .
								"ORDER BY title");
								
								
			$community_names = db_query("SELECT ident, name FROM users WHERE ident IN (" . implode(",", $community_ids) . ")");
			$indexed_names = array();
			foreach($community_names as $index => $community){
				$indexed_names["community" . $community->ident] = $community->name;
			}
			
			$cnt = count($results);
			for($i=0;$i<$cnt;$i++){
				$results[$i]->title = $results[$i]->title . " @ " . stripslashes($indexed_names[$results[$i]->access]);
			}
		break;
		
	}
	
	$run_result = $results;
	
?>