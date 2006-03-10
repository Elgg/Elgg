<?php
	global $calendar_id;
	
	$month = (int) $parameter[0];
	$year = (int) $parameter[1];
	$context = (int) $parameter[2];
	
	$min_time = mktime(0, 0, 0, $month, 1, $year);
	$max_time = mktime(24, 59, 59, $month, date("t", mktime(0, 0, 0, $month+1, 0, $year)), $year);

	$results = array();
	
	switch($context){
		case "private":
		default:
			
			$results = db_query("SELECT ident, title, date_start, date_end, access FROM event " . 
								"WHERE (date_start >= ". $min_time ." ".
								"AND date_start <= ". $max_time ." ".
								"OR date_end <= ". $max_time ." ".
								"AND date_end >=". $min_time ." ) " .
								"AND (access='user" . $_SESSION["userid"] . "' " .
								"OR access='PUBLIC' " .
								"OR (access='LOGGED_IN' " .
								"AND owner=" . $calendar_id . ")) " .
								"ORDER BY title");
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
				
				$query = "SELECT ident, title, date_start, date_end, access FROM event " .
									"WHERE (date_start >= ". $min_time ." ".
									"AND date_start <= ". $max_time ." ".
									"OR date_end <= ". $max_time ." ".
									"AND date_end >=". $min_time ." ) " .
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
			
			if(count($valid_access)){
				$results = db_query("SELECT ident, title, date_start, date_end, access FROM event " .
									"WHERE access IN (" . implode(",", $valid_access) . ") " .
									"AND (date_start >= ". $min_time ." ".
									"AND date_start <= ". $max_time ." ".
									"OR date_end <= ". $max_time ." ".
									"AND date_end >=". $min_time ." ) " .
									"ORDER BY title");
				
				//find groups that a community may belong to a group and get event postings for those as well
				$group_ids = db_query("SELECT group_id FROM groups " .
									  "WHERE user_id IN (" . implode(",", $community_ids) . ")");
				
				$group_event_query = "SELECT ident, title, date_start, date_end FROM event " .
									 "WHERE access IN (";
				$num_groups = count($group_ids);
				for($i=0;$i<$num_groups;$i++){
					if ($i != 0) {
						$group_event_query .= ",'group" . $group_ids[$i]->ident ."'";
					} else {
						$group_event_query .= "'group" . $group_ids[$i]->ident ."'";
					}
				}
				
				$group_event_query .= ")";
				
				$group_events = db_query($group_event_query);
				
				$results = array_merge($results, $group_events);
				
				$community_names = db_query("SELECT name FROM users WHERE ident IN (" . implode(",", $community_ids) . ")");
				$index_names = array();
				foreach($community_names as $index => $community){
					$indexed_names["community" . $community->ident] = $community->name;
				}
				
				$cnt = count($results);
				for($i=0;$i<$cnt;$i++){
					if(substr($results[$i]->access, 0, strlen("community")) == "community") {
						$results[$i]->title = $results[$i]->title . " @ " . stripslashes($indexed_names[$results[$i]->access]);
					}
				}
				
			}else{
				$messages[] = gettext("you are not part of any communities");
				$_SESSION["messages"] = $messages;
				header("Location: " . url . strtolower(run("users:id_to_name", $_SESSION["userid"])) . "/calendar/");
			}
			
		break;
		
	}
	$run_result = $results;
?>