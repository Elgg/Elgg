<?php
	$event = $parameter;
	$url = url;
	
	$text_start_date = gettext("Start Date:");
	$text_end_date = gettext("End Date:");
	$text_keyword = gettext("Keywords:");
	$text_location = gettext ("Location:");
	$text_description = gettext("Description:");
	$text_start_time = gettext("Start Time:");
	$text_end_time = gettext("End Time:");
	
	$user_id = db_query("SELECT owner FROM calendar " .
						 "WHERE ident=" . $event->owner);
	
	$user_info = db_query("SELECT * FROM users " .
						 " WHERE ident=({$user_id[0]->owner})");
	
	$event_date = getdate($event->date_start);
	if($event_date_seperator == null || $event_date_seperator != null && $event_date_seperator["mday"] != $event_date["mday"]){
		$event_date_seperator = $event_date;
		
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
	$event_body .= "<b>" . $text_location . "</b> " . $event->location ."<br/><br/>";
	$event_body .= "<b>" . $text_description . "</b><br/>" . $event->description;
	
	$keywords = run("display:output_field", array("", "keywords", "calendar", "calendar", $event->ident, $event->owner));
	
	if($keywords != ""){
		$event_body .= '<p class="weblog_keywords">
							<small>' . $text_keyword . ' ' . $keywords . '</small>
						</p>';
	}
	
	
	if($event->owner == $calendar_id){	
		$event_body .= <<< END
			<p>
				<small>
					[<a href="{$url}_calendar/edit_event.php?event_id={$event->ident}" >$edit</a>]
					[<a href="{$url}_calendar/index.php?action=delete_event&amp;event_id={$event->ident}&amp;selected_year={$_REQUEST['selected_year']}&amp;selected_month={$_REQUEST['selected_month']}&amp;selected_day={$_REQUEST['selected_day']}" onClick="return confirm('$returnConfirm')">$delete</a>] 
					</small>
				</p>	
END;
		}
		
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
	
?>