<?php
	global $calendar_id;
	global $months;
	
	run("weblogs:init");
	
	$events = array();
	$selected_month = null;
	$selected_year = null;
	$selected_day = null;
	$context = null;
	$date = null;
	
	$friend_id = null;
	$community_id = null;
	$event_id = null;
	
	if(count($parameter) == 4){
		$selected_month = (int) $parameter[0];
		$selected_year = (int) $parameter[1];
		$selected_day = (int) $parameter[2];
		$context = $parameter[3];
		
		$date = getdate(mktime(0, 0, 0, $selected_month, $selected_day, $selected_year));
		$date = $date["month"] . " " . (strlen($date["mday"])==1 ? "0" . $date["mday"] : $date["mday"]) . ", " . $date["year"];
		
		$events = run("calendar:get_daily_event_listings", array($selected_month, $selected_year, $selected_day, $context));
	}else if(count($parameter) == 2 && $parameter[1] == "friends"){
		$friend_id = (int) $parameter[0];
		$events = run("calendar:get_friend_event_listings", array($friend_id));
		
	}else if(count($parameter) == 2 && $parameter[1] == "communities"){
		$community_id = (int) $parameter[0];
		$events = run("calendar:get_community_event_listings", array($community_id));
		
	}else if(count($parameter) == 2 && $parameter[1] == "tags"){
		$event_id = (int) $parameter[0];
		$events = run("calendar:get_event", array($event_id));
	}
	
	
	
	$user_info = null;
	$user_id = null;
	$icon = null;
	$event_body = null;
	$start_date = null;
	$end_date = null;
	$start_time = null;
	$end_time = null;
	$commentsMenu = null;
	$event_date_seperator = null;
	$event_date = null;
	
	$export = gettext("Export Event");
	$edit = gettext("Edit");
	$delete = gettext("Delete");
	$returnConfirm = gettext("Are you sure you want to delete this event?");

	if(count($events)==0 || !$events){
		if($event_id == null && $friend_id == null && $community_id == null)
			$messages[] = gettext("Please select another date no events exist on the selected day");
		else if($friend_id != null)
			$messages[] = gettext("No events have been posted for this user");
		else if($community_id != null)
			$messages[] = gettext("No events have been posted for this community");
			
		$_SESSION["messages"] = $messages;
		$_SESSION["request"] = $_REQUEST;
		
		if($event_id == null && $friend_id == null && $community_id == null)
			header("Location: " . url . "_calendar/index.php?selected_month=" . ($selected_month + 1)  . "&selected_year=" . $selected_year . "&context=" . $context);
	}else{
	
		$text_start_date = gettext("Start Date:");
		$text_end_date = gettext("End Date:");
		$text_keyword = gettext("Keywords:");
		$text_location = gettext ("Location:");
		$text_description = gettext("Description:");
		$text_start_time = gettext("Start Time:");
		$text_end_time = gettext("End Time:");
		
		foreach($events as $event){
			$user_id = db_query("SELECT owner FROM calendar " .
						"WHERE ident=" . $event->owner);
						
			$user_info = db_query("SELECT * FROM users " .
								 " WHERE ident=({$user_id[0]->owner})");
			
			$event_date = getdate($event->date_start);
			if($event_date_seperator == null || $event_date_seperator != null && $event_date_seperator["mday"] != $event_date["mday"]){
				$event_date_seperator = $event_date;
				
				$run_result .= "<h2 class=\"weblogdateheader\">" . $event_date_seperator["mday"] . " " . strtolower($months[$event_date_seperator['month']]) . " " . $event_date_seperator["year"] . "</h2>\n";
			}
							 			   
			$icon=null;
			if($user_info[0]->icon==-1){
				$icon = "default.png";
			}else{
				$icon = db_query("select filename from icons where ident = ".$user_info[0]->icon);
				$icon = $icon[0]->filename;
			}
			
				
			//set up event post body...should sub in time when we get around to it
			$start_date = getdate($event->date_start);
			$end_date = getdate($event->date_end);
			$start_time = ($start_date["hours"]>12 ? $start_date["hours"]-12 : $start_date["hours"]) . ":" . (strlen($start_date["minutes"])==1 ? "0" . $start_date["minutes"] : $end_date["minutes"]) . " " . ($start_date["hours"]>=12 ? "PM" : "AM");
			$end_time = ($end_date["hours"]>12 ? $end_date["hours"]-12 : $end_date["hours"]) . ":" . (strlen($end_date["minutes"])==1 ? "0" . $end_date["minutes"] : $end_date["minutes"]) . " " . ($end_date["hours"]>=12 ? "PM" : "AM");
			
			
			$event_body = "<b>" . $text_start_date . "</b> " . $start_date["mday"] . "-" . $start_date["mon"] . "-" . $start_date["year"] ."&nbsp;&nbsp;&nbsp;";
			$event_body .= "<b>" . $text_end_date . "</b> " . $end_date["mday"] . "-" . $end_date["mon"] . "-" . $end_date["year"] ."<br/>";
			$event_body .= "<b>" . $text_start_time . "</b> " . $start_time ."<br/>";
			$event_body .= "<b>" . $text_end_time . "</b> " . $end_time ."<br/>";
			$event_body .= "<b>" . $text_location . "</b> " . stripslashes($event->location) ."<br/><br/>";
			$event_body .= "<b>" . $text_description . "</b><br/><br/>" . run("weblogs:text:process", stripslashes($event->description));
			
			$keywords = run("display:output_field", array("", "keywords", "calendar", "calendar", $event->ident, $event->owner));
	
			if($keywords != ""){
				$event_body .= '<p class="weblog_keywords">
									<small>' . $text_keyword . ' ' . $keywords . '</small>
								</p>';
			}
						
			$event_body .= '
				<p>
					<small>
						[<a href="' . url. '_calendar/export_event.php?event_id=' . $event->ident . '" >' . $export . '</a>]	
			';
			
			
			if($event->owner == $calendar_id){	
				$event_body .= '
					
						[<a href="' . url. '_calendar/edit_event.php?event_id=' . $event->ident . '" >' . $edit . '</a>]
						[<a href="' . url. '_calendar/index.php?action=delete_event&event_id=' . $event->ident . '&selected_year=' . $_GET["selected_year"] . '&selected_month=' . $_GET["selected_month"] . '&selected_day=' . $_GET["selected_day"] . '" onClick="return confirm("{$returnConfirm")">' . $delete . '</a>] 
					
				';
			}
			
			$event_body .= '
					</small>
				</p>
			';
			
			$run_result .= run("templates:draw", array(
										'context' => 'weblogpost',
										'date' => $date,
										'username' => $user_info[0]->username,
										'usericon' => $icon,									
										'body' => $event_body,
										'fullname' => $user_info[0]->name,
										'title' => stripslashes($event->title) . "<a name='{$event->ident}'></a>",
										'comments' => ""
									)
									);	
									
		}
	}
?>