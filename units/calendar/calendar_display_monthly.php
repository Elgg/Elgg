<?php
	
	global $days_of_week;
	global $selected_month;
	global $selected_year;
	global $context;
	global $months;
	
	
	if(count($parameter)==3){
		$selected_month = ((int)$parameter[0]) - 1;
		$selected_year = ((int)$parameter[1]);
		$context = $parameter[2];
	} elseif (count($parameter)==1){
		$selected_month = date("m");
		$selected_year = date("Y");
		$context = $parameter[0];
	}
	
	if ($context == null) {
		$context = "private";
	}
	
	$monthly_events = run("calendar:get_monthly_event_listings", array($selected_month, $selected_year, $context));
	$num_events = count($monthly_events);
	
	$date_info = getdate(mktime(0, 0, 0, $selected_month+1, 0, $selected_year));
	$num_days = date("t", mktime(0, 0, 0, $selected_month+1, 0, $selected_year));
	
	
	//load event array indexed on date for quick access
	$events = array();
	for($i=0;$i<$num_events;$i++){	
		$start_time = $monthly_events[$i]->date_start;
		$start_time = getdate($start_time);
		
		$end_time = $monthly_events[$i]->date_end;
		$end_time = getdate($end_time);
				
		if($start_time["mon"] < $end_time["mon"] && $date_info["mon"] < $end_time["mon"] || 
				$start_time["year"] < $end_time["year"] && $date_info["mon"] < $end_time["mon"]){
			//event spans months/years, but this month is before the end date
			
			for($j=$start_time["mday"];$j<=$num_days;$j++){
				$events[$j][] = array("title" => stripslashes((strlen($monthly_events[$i]->title) > 9 ? str_replace(" ", "&nbsp;", substr($monthly_events[$i]->title, 0, 6) . "...") : $monthly_events[$i]->title)) . "&nbsp;<img style=\"border:none;\" src=\"" . url . "units/calendar/images/arrow-right.gif\" alt=\"arrow\">",
									  "ident" => $monthly_events[$i]->ident,
									  "access" => $monthly_events[$i]->access);
			}
			
		} else {
			//either the event spans months/years and the end month is the same as this month
			//or the start and end months are the same and are equal to this month
			$start_day = null;
			$end_day = null;
			
			 if($start_time["mon"] < $end_time["mon"] && $date_info["mon"] == $end_time["mon"] ||
			 		$start_time["year"] < $end_time["year"] && $date_info["mon"] == $end_time["mon"] && $date_info["year"] == $end_time["year"]){
			 			
			 	$start_day = 1;
			 } elseif ($start_time["mon"] == $end_time["mon"] && $start_time["year"] == $end_time["year"] &&
			 			$end_time["mon"] == $date_info["mon"] && $end_time["year"] == $date_info["year"]){
			 				
			 	$start_day = $start_time["mday"];
			 }
			 
			 if($date_info["year"] < $end_time["year"])
			 	$end_day = $num_days;
			 else if($date_info["year"] == $end_time["year"])
			 	$end_day = $end_time["mday"];
			 
						 
			 for ($j=$start_day;$j<=$end_day;$j++){
				if($j!=$end_time["mday"]){
					$events[$j][] = array("title" => stripslashes((strlen($monthly_events[$i]->title) > 9 ? str_replace(" ", "&nbsp;", substr($monthly_events[$i]->title, 0, 6) . "...") : $monthly_events[$i]->title)) . "&nbsp;<img style=\"border:none;\" src=\"" . url . "units/calendar/images/arrow-right.gif\" alt=\"arrow\">",
										  "ident" => $monthly_events[$i]->ident,
										  "access" => $monthly_events[$i]->access);
				} else {
					$events[$j][] = array("title" => stripslashes((strlen($monthly_events[$i]->title) > 9 ? substr($monthly_events[$i]->title, 0, 6) ."..." : $monthly_events[$i]->title)),
										  "ident" => $monthly_events[$i]->ident,
										  "access" => $monthly_events[$i]->access);
				}
			}
			 
		}
	}	
	
	
	//get time for selected month and year
	$time = mktime(0, 0, 0, $selected_month+1, 0, $selected_year);
	
	//get date info
	$date_info = getdate($time);
	$first_day = date("w", mktime(0,0,0,$selected_month, 1, $selected_year));
	$num_days = date("t", $time);
	$num_weeks = $num_days / 7;
	$num_remaining_days = $num_days % 7;
	
	$more_events = gettext("more event");
	$more_events = str_replace(" ", "&nbsp;", $more_events);
	
	//display month and year
	$body = "<table width='100%' style='margin-right: 10px;'><tr><td width='20%'>" . run("templates:draw", array(
								'context' => 'activemonthbox',
								'contents' => $months[$date_info["month"]] ."&nbsp;". $date_info["year"]
							)
							);
	
	$body .= "</td><td>";
	
	$navigation = run("calendar:display:monthly_navigation", array($selected_month+1, $selected_year, $context));
	
	$body .= run("templates:draw", array(
								'context' => 'monthlynavigationbox',
								'monthbefore' => $navigation[0],
								'monthafter' => $navigation[1]
							)
							);
	
	$body .= "</td></tr></table>";
	
	//draw days of the month
	$body .='<table width="100%" style="border: 1px solid #000000;"><tr>';
	
	//draw days of the week
	for($i=0;$i<count($days_of_week);$i++){
		$body .= '<td width="14%" style="border: 1px solid #000000;" align="center" valign="top">';
		
		$body .= run("templates:draw", array(
								'context' => 'dayofweekbox',
								'contents' =>$days_of_week[$i]
							)
							);
		
		$body .= "</td>";
	}
	
	$body .= "</tr>";
	
	//draw first week
	$body .= "<tr>";
	
	$day_count = 1;
	$eventStr="";
	
	for ($i=0; $i<7; $i++) {
		if ($i >= $first_day) {
			$eventStr = "";
			if ($events[$day_count]!=null) {
				$event_count = 0;
				foreach ($events[$day_count] as $index => $event) {
					if ($event_count < 3) {
						if ($event["access"] == "LOGGED_IN") {
							$eventStr .= run("templates:draw", array(
								'context' => 'loggedinevent',
								'title' => $event["title"],
								'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
								)
							);
						} elseif ($event["access"] == "user" . $_SESSION["userid"]) {
							$eventStr .= run("templates:draw", array(
								'context' => 'privateevent',
								'title' => $event["title"],
								'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
								)
							);
						} else {
							$eventStr .= run("templates:draw", array(
								'context' => 'publicevent',
								'title' => $event["title"],
								'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
								)
							);
						}
					} else {
						if ((count($events[$day_count]) - 3) > 1) {
							$more_events = $more_events . "s";
						}
						
						$eventStr .="<br/><a style=\"font-size:smaller; top-margin:5px; bottom-margin:0px\" href='" . url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}" . "'>" . (count($events[$day_count]) - 3) . "&nbsp;{$more_events}</a>";
						$more_events = substr($more_events, 0, strlen($more_events)-1);
						break;
					}
					$event_count++;
				}
			}
			
			$body .= "<td valign='top' height='100px' style='border: 1px solid #000000;'>";
			
			$body .= run("templates:draw", array(
								'context' => 'datelink',
								'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}",
								'date' => $day_count
							)
							);
			
			$day_count++;
			$body .= $eventStr;
			$body .= "</td>"; 
		} else {
			$body .= "<td height='100px'>&nbsp;</td>";
		}
	}
	$body .= "</tr>";
		
	//draw middle weeks
	for($i=1;$i<$num_weeks;$i++){
		$body .= "<tr>";
		for($j=0;$j<7;$j++){
			if($day_count<=$num_days){
				$eventStr="";
				if($events[$day_count]!=null){
					$event_count = 0;
					foreach($events[$day_count] as $index => $event){
						
						if($event_count<3){
							if($event["access"] == "LOGGED_IN"){
								$eventStr .= run("templates:draw", array(
									'context' => 'loggedinevent',
									'title' => $event["title"],
									'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
									)
								);
							} elseif ($event["access"] == "user" . $_SESSION["userid"]){
								$eventStr .= run("templates:draw", array(
									'context' => 'privateevent',
									'title' => $event["title"],
									'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
									)
								);
							} else {
								$eventStr .= run("templates:draw", array(
									'context' => 'publicevent',
									'title' => $event["title"],
									'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
									)
								);
							}
						} else {
							if((count($events[$day_count]) - 3) > 1) {
								$more_events = $more_events . "s";
							}
							
							$eventStr .="<br/><a style=\"font-size:smaller; top-margin:5px; bottom-margin:0px\" href='" . url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}" . "'>" . (count($events[$day_count]) - 3) . "&nbsp;{$more_events}</a>";
							$more_events = substr($more_events, 0, strlen($more_events)-1);
							break;
						}
							
						$event_count++;
					}
				}
				$body .= "<td valign='top' height='100px' style='border: 1px solid #000000;'>";
				
				$body .= run("templates:draw", array(
								'context' => 'datelink',
								'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}",
								'date' => $day_count
							)
							);
							
				$day_count++;
				$body .= $eventStr;
				$body .= "</td>";
			} else {
				$body .= "<td height='100px'>&nbsp;</td>";
			}
		}
		$body .= "</tr>";
	}
				
	//draw final week
	if ($day_count <= $num_days) {
		$body .= "<tr>";
		for ($i=0; $i<7; $i++) {
			if ($day_count <= $num_days) {
				$eventStr="";
				if ($events[$day_count]!=null) {
					$event_count = 0;
					foreach($events[$day_count] as $index => $event){
						if ($event_count < 3) {
							if ($event["access"] == "LOGGED_IN") {
								$eventStr .= run("templates:draw", array(
									'context' => 'loggedinevent',
									'title' => $event["title"],
									'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
									)
								);
							} elseif ($event["access"] == "user" . $_SESSION["userid"]){
								$eventStr .= run("templates:draw", array(
									'context' => 'privateevent',
									'title' => $event["title"],
									'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
									)
								);
							} else {
								$eventStr .= run("templates:draw", array(
									'context' => 'publicevent',
									'title' => $event["title"],
									'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}#{$event["ident"]}",
									)
								);
							}
						} else {
							if ((count($events[$day_count]) - 3) > 1) {
								$more_events = $more_events . "s";
							}
							
							$eventStr .="<a style=\"font-size:smaller; top-margin:5px; bottom-margin:0px\" href='" . url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}" . "'>" . (count($events[$day_count]) - 3) . "&nbsp;{$more_events}</a>";
							$more_events = substr($more_events, 0, strlen($more_events)-1);
							break;
						}
						$event_count++;
					}
				}
				
				$body .= "<td valign='top' height='100px' style='border: 1px solid #000000;'>";
				
				$body .= run("templates:draw", array(
								'context' => 'datelink',
								'url' => url . "_calendar/view_events.php?selected_year={$selected_year}&amp;selected_month={$selected_month}&amp;selected_day={$day_count}&amp;context={$context}",
								'date' => $day_count
							)
							);
							
				$day_count++;
				$body .= $eventStr;
				$body .= "</td>"; 
			} else
				$body .= "<td height='100px'>&nbsp;</td>";
		}
		$body .= "</tr>";
	}
	$body .= "</table><br />";
	
	if ($context == "private") {
		$body .= '<table width="100%" style="border: 1px solid #000000;"><tr><td>';
		
		$body .= run("templates:draw", array(
								'context' => 'publiceventlegend',
								'content' => gettext('Public&nbsp;events')
							)
							);
		
		$body .= run("templates:draw", array(
								'context' => 'privateeventlegend',
								'content' => gettext('Private&nbsp;events')
							)
							);
		
		$body .= run("templates:draw", array(
								'context' => 'loggedineventlegend',
								'content' => gettext('Logged&nbsp;in&nbsp;user&nbsp;events')
							)
							);
		
		$body .= '</td></tr></table><br />';
	}
	$run_result = $body;
?>