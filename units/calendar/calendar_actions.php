<?php
	
//	var_dump($_REQUEST);
//	exit;
	if(isset($_REQUEST["action"])){

		
		switch($_REQUEST["action"]){
			
			case "calendar:event:create":
				
				$event_title = trim($_REQUEST["event_title"]);
				$event_description = trim($_REQUEST["event_description"]);
				$event_keywords = trim($_REQUEST["event_keywords"]);
				$event_access = trim($_REQUEST["event_access"]);
				$owner_id = trim($_REQUEST["owner_calendar_id"]);
				$event_location = trim($_REQUEST["event_location"]);
				
				$date = getdate(time());
		
				$start_date = trim(substr($_REQUEST["start_date"], 0, strpos($_REQUEST["start_date"], "[")));
				$start_time = substr($_REQUEST["start_date"], strpos($_REQUEST["start_date"], "[")+1, strpos($_REQUEST["start_date"], "]") - strpos($_REQUEST["start_date"], "[") - 1);
				$start_am_pm = substr($start_time, strpos($start_time, " ")+1, 3);
				$start_time = explode(":", substr($start_time, 0, 5)); 
				
				$end_date = trim(substr($_REQUEST["end_date"], 0, strpos($_REQUEST["end_date"], "[")));
				$end_time = substr($_REQUEST["end_date"], strpos($_REQUEST["end_date"], "[")+1, strpos($_REQUEST["end_date"], "]") - strpos($_REQUEST["end_date"], "[") - 1);
				$end_am_pm = substr($end_time, strpos($end_time, " ")+1, 3);
				$end_time = explode(":", substr($end_time, 0, 5));
				
				//if user enters in H:MM instead of HH:MM place a 0 before it
				if(strlen($start_time[0])==1){
					$_REQUEST["start_date"] = substr($_REQUEST["start_date"], 0, strpos($_REQUEST["start_date"], "[")+1) . "0" . substr($_REQUEST["start_date"], strpos($_REQUEST["start_date"], "[")+1, strlen($_REQUEST["start_date"]));
				}
				
				if(strlen($end_time[0])==1){
					$_REQUEST["end_date"] = substr($_REQUEST["end_date"], 0, strpos($_REQUEST["end_date"], "[")+1) . "0" . substr($_REQUEST["end_date"], strpos($_REQUEST["end_date"], "[")+1, strlen($_REQUEST["end_date"]));
				}
				
				//validate start time is less than end time
				$start_time_components = $start_time;
				$end_time_components = $end_time;
				
				if($start_am_pm == "PM") {
					$start_time_components[0] = 12 + $start_time_components[0];
				}
				
				if($end_am_pm == "PM") {
					$end_time_components[0] = 12 + $end_time_components[0];
				}
				
				
				$start_components = split("-", $start_date);
				$end_components = split("-", $end_date);
				//var_dump($_REQUEST["start_date"], $_REQUEST["end_date"]);
				
				//validate date formats
				if(count($start_components)!=3 || count($end_components)!=3 || count($start_time)!=2 || count($end_time)!=2 ||
						 $start_time[0]<1 || $start_time[0]>12 || $start_time[1]<0 || $start_time[1]>59 ||
						 $end_time[0]<1 || $end_time[0]>12 || $end_time[1]<0 || $end_time[1]>59 ||
						 (strtolower($start_am_pm)!="am" && strtolower($start_am_pm)!="pm") ||
						 (strtolower($end_am_pm)!="am" && strtolower($end_am_pm)!="pm")){
					
					$messages[] = gettext("Please enter the dates in the format DD-MM-YYYY [HH:MM AM/PM]");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/add_event.php");
					
				}else if(mktime(23, 59, 59, $start_components[1], $start_components[0], $start_components[2])<$date[0]){
					$messages[] = gettext("Please enter an event start date that is not in the past");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/add_event.php?reset=true");
					
				}else if(mktime(23, 59, 59, $start_components[1], $start_components[0], $start_components[2]) > mktime(23, 59, 59, $end_components[1], $end_components[0], $end_components[2])){
					$messages[] = gettext("Please make sure your event start date occurs before the event end date");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/add_event.php");
					
				}else if(mktime($start_time_components[0], $start_time_components[1], 0, $start_components[1], $start_components[0], $start_components[2]) > mktime($end_time_components[0], $end_time_components[1], 0, $end_components[1], $end_components[0], $end_components[2])){
					$messages[] = gettext("Please make sure your event start time occurs before the event end time");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/add_event.php");
				
				}else if(!checkdate($start_components[1], $start_components[0], $start_components[2]) ||
						 !checkdate($end_components[1], $end_components[0], $end_components[2])){
					
					$messages[] = gettext("Please enter a valid date");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/add_event.php");
					
				}else if(!$event_title){
					$messages[] = gettext("Please enter a title for your event.");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/add_event.php");
				}else{
					db_query("INSERT INTO event " .
							"SET owner = ". $owner_id .", ".
							"title = '". $event_title ."', " .
							"description = '". $event_description ."', " .
							"access = '". $event_access ."', " .
							"location = '". $event_location ."', " .
							"date_start = ". mktime($start_time_components[0], $start_time_components[1], 0, $start_components[1], $start_components[0], $start_components[2]) .", " .
							"date_end = ". mktime($end_time_components[0], $end_time_components[1], 0, $end_components[1], $end_components[0], $end_components[2]));
					
					$insert_id = db_id();

					//save tags if isset
					if(isset($event_keywords)){
						$value = $event_keywords;
						$value = str_replace("\n","",$value);
						$value = str_replace("\r","",$value);
						$keyword_list = explode(",",$value);
						sort($keyword_list);
						
						if (sizeof($keyword_list) > 0) {
							foreach($keyword_list as $key => $list_item) {
								$list_item = addslashes(trim($list_item));
								db_query("INSERT INTO tags" .
										" SET tagtype = 'calendar'," .
										" access = '$event_access'," .
										" tag = '$list_item'," .
										" ref = $insert_id," .
										" owner = " . $owner_id);
							}
						}
					}
					
					header("Location: " . url . "_calendar/index.php?context=private");
				}
				
			break;
			
			case "calendar:event:update":
			
				$event_id = (int) $_REQUEST["event_id"];
				$event_title = trim($_REQUEST["event_title"]);
				$event_description = trim($_REQUEST["event_description"]);
				$event_keywords = trim($_REQUEST["event_keywords"]);
				$event_access = trim($_REQUEST["event_access"]);
				$owner_id = trim($_REQUEST["owner_calendar_id"]);
				$event_location = trim($_REQUEST["event_location"]);
				
				$date = getdate(time());	
		
				$start_date = trim(substr($_REQUEST["start_date"], 0, strpos($_REQUEST["start_date"], "[")));
				$start_time = trim(substr($_REQUEST["start_date"], strpos($_REQUEST["start_date"], "[")+1, strpos($_REQUEST["start_date"], "]") - strpos($_REQUEST["start_date"], "[") - 1));
				$start_am_pm = substr($start_time, strpos($start_time, " ")+1, 3);
				$start_time = explode(":", substr($start_time, 0, 5)); 
				
				$end_date = trim(substr($_REQUEST["end_date"], 0, strpos($_REQUEST["end_date"], "[")));
				$end_time = trim(substr($_REQUEST["end_date"], strpos($_REQUEST["end_date"], "[")+1, strpos($_REQUEST["end_date"], "]") - strpos($_REQUEST["end_date"], "[") - 1));
				$end_am_pm = substr($end_time, strpos($end_time, " ")+1, 3);
				$end_time = explode(":", substr($end_time, 0, 5));
				
				
				//if user enters in H:MM instead of HH:MM place a 0 before it
				if(strlen($start_time[0])==1){
					$_REQUEST["start_date"] = substr($_REQUEST["start_date"], 0, strpos($_REQUEST["start_date"], "[")+1) . "0" . substr($_REQUEST["start_date"], strpos($_REQUEST["start_date"], "[")+1, strlen($_REQUEST["start_date"]));
				}
				
				if(strlen($end_time[0])==1){
					$_REQUEST["end_date"] = substr($_REQUEST["end_date"], 0, strpos($_REQUEST["end_date"], "[")+1) . "0" . substr($_REQUEST["end_date"], strpos($_REQUEST["end_date"], "[")+1, strlen($_REQUEST["end_date"]));
				}
				
				//validate start time is less than end time
				$start_time_components = $start_time;
				$end_time_components = $end_time;
				if($start_am_pm == "PM")
					$start_time_components[0] = 12 + $start_time_components[0];			
				
				if($end_am_pm == "PM")
					$end_time_components[0] = 12 + $end_time_components[0];
				
				
				$start_components = split("-", $start_date);
				$end_components = split("-", $end_date);
				//var_dump($start_am_pm);
						 				
				//validate date formats
				if(count($start_components)!=3 || count($end_components)!=3 || count($start_time)!=2 || count($end_time)!=2 ||
						 $start_time[0]<1 || $start_time[0]>12 || $start_time[1]<0 || $start_time[1]>59 ||
						 $end_time[0]<1 || $end_time[0]>12 || $end_time[1]<0 || $end_time[1]>59 ||
						 (strtolower($start_am_pm)!="am" && strtolower($start_am_pm)!="pm") ||
						 (strtolower($end_am_pm)!="am" && strtolower($end_am_pm)!="pm")){
										
					$messages[] = gettext("Please enter the dates in the format DD-MM-YYYY [HH:MM AM/PM]");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/edit_event.php?" . $event_id);
					
				}else if(mktime(23, 59, 59, $start_components[1], $start_components[0], $start_components[2])<$date[0]){
					$messages[] = gettext("Please enter an event start date that is not in the past");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/edit_event.php?" . $event_id . "&reset=true");
					
				}else if(mktime(23, 59, 59, $start_components[1], $start_components[0], $start_components[2]) > mktime(23, 59, 59, $end_components[1], $end_components[0], $end_components[2])){
					$messages[] = gettext("Please make sure your event start date occurs before the event end date");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/edit_event.php?event_id=" . $event_id);
					
				}else if(mktime($start_time_components[0], $start_time_components[1], 0, $start_components[1], $start_components[0], $start_components[2]) > mktime($end_time_components[0], $end_time_components[1], 0, $end_components[1], $end_components[0], $end_components[2])){
					$messages[] = gettext("Please make sure your event start time occurs before the event end time");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/edit_event.php?" . $event_id);
				
				}else if(!checkdate($start_components[1], $start_components[0], $start_components[2]) ||
						 !checkdate($end_components[1], $end_components[0], $end_components[2])){
					
					$messages[] = gettext("Please enter a valid date");
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/edit_event.php?event_id=" . $event_id);
					
				}else if(!$event_title){
					$messages[] = "Please enter a title for your event.";
					$_SESSION["messages"] = $messages;
					$_SESSION["request"] = $_REQUEST;
					header("Location: " . url . "_calendar/edit_event.php?event_id=". $event_id);
				}else{
					db_query("UPDATE event " .
							"SET ".
							"title = '". $event_title ."', " .
							"description = '". $event_description ."', " .
							"access = '". $event_access ."', " .
							"location = '". $event_location ."', " .
							"date_start = ". mktime($start_time_components[0], $start_time_components[1], 0, $start_components[1], $start_components[0], $start_components[2]) .", " .
							"date_end = ". mktime($end_time_components[0], $end_time_components[1], 0, $end_components[1], $end_components[0], $end_components[2]) . " " .
							"WHERE ident = " . $event_id);
					
					
					//save tags if isset
					
					if(isset($event_keywords)){
						$value = $event_keywords;
						$value = str_replace("\n","",$value);
						$value = str_replace("\r","",$value);
						$keyword_list = explode(",",$value);
						sort($keyword_list);

						if (sizeof($keyword_list) > 0) {
							db_query("DELETE FROM tags " .
										 "WHERE ref = {$event_id}");
							foreach($keyword_list as $key => $list_item) {
								$list_item = addslashes(trim($list_item));
																		
								db_query("INSERT INTO tags" .
										" SET tagtype = 'calendar'," .
										" access = '$event_access'," .
										" tag = '$list_item'," .
										" ref = $event_id," .
										" owner = " . $owner_id);
							}
						}
					}
					
					header("Location: " . url . "_calendar/index.php?context=private");
				}
			
			break;
			
			case "delete_event":
				$eventid = (int) $_REQUEST["event_id"];
				db_query("DELETE FROM event WHERE ident=" . $eventid);
				
				header("Location: " . url . "_calendar/view_events.php?selected_year=" . intval($_REQUEST["selected_year"]) . "&selected_month=" . intval($_REQUEST["selected_month"]) . "&selected_day=" . intval($_REQUEST["selected_day"]));
			break; 
		}
	}
	
?>