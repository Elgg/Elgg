<?php

	include("calendar_init.php");

	// Preview template
	
	$monthly_events = array(
							array('title' => 'public event', 'access' => 'PUBLIC', 'ident' => '1'),
							array('title' => 'private event', 'access' => 'user1', 'ident' => '1'),
							array('title' => 'logged in user event', 'access' => 'LOGGED_IN', 'ident' => '1'),
							array('title' => 'public event', 'access' => 'PUBLIC', 'ident' => '1'),
							array('title' => 'private event', 'access' => 'user1', 'ident' => '1'),
							array('title' => 'logged in user event', 'access' => 'LOGGED_IN', 'ident' => '1')
							);
	
	$num_events = count($monthly_events);
	for($i=0;$i<$num_events;$i++){
		for($j=0;$j<=4;$j++){
			$events[$j][] = array("title" => stripslashes((strlen($monthly_events[$i]['title']) > 9 ? str_replace(" ", "&nbsp;", substr($monthly_events[$i]['title'], 0, 8) ."...") : $monthly_events[$i]['title'])) . "&nbsp;<img style=\"border:none\" src=\"" . url . "units/calendar/images/arrow-right.gif\" alt=\"arrow\">",
									"ident" => $monthly_events[$i]['ident'],
									"access" => $monthly_events[$i]['access']);
		}
	}

	//get date info
	$date_info = getdate(time());

	$first_day = date("w", mktime(0,0,0,$date_info['mon'], 1, $date_info['year']));
	
	$more_events = gettext("more event");
	$more_events = str_replace(" ", "&nbsp;", $more_events);
		
	//display month and year
	$body = "<table width='100%' style='margin-right: 10px;'><tr><td width='20%'>" . run("templates:draw", array(
								'context' => 'activemonthbox',
								'contents' => gettext($date_info["month"]) ."&nbsp;". $date_info["year"]
							)
							);
							
	$body .= "</td><td>";
	
	$navigation = array(getdate(mktime(0, 0, 0, ($date_info['mon']+1 != 1 ? $date_info['mon']+1 - 1 : 12), 0, ($date_info['mon']+1 != 1 ? $date_info['year'] : $date_info['year'] - 1))),
		getdate(mktime(0, 0, 0, ($date_info['mon']+1 != 13 ? $date_info['mon']+1 + 1 : 2), 0, ($date_info['mon']+1 != 13 ? $date_info['year'] : $date_info['year'] + 1))));
	
	$body .= run("templates:draw", array(
								'context' => 'monthlynavigationbox',
								'monthbefore' => "<a href=\"\">&lt;&lt;&nbsp;{$navigation[0]["month"]}</a>",
								'monthafter' => "<a href=\"\">{$navigation[1]["month"]}&nbsp;&gt;&gt;</a>"
								)
				);
				
	$body .= "</td></tr></table>";
	
	//draw days of the month
	$body .='<table width="100%" style="border: solid #000000; border-width: 1px;"><tr>';
	
	//draw days of the week
	for($i=0;$i<count($days_of_week);$i++){
		$body .= "<td width='14%' style='border: solid #000000; border-width: 1px;' align='center' valign='top'>";
		
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
	
	for($i=0;$i<7;$i++){
		if($i>=$first_day){
			$eventStr="";
			if($events[$day_count]!=null){
				$event_count = 0;
				
				foreach($events[$day_count] as $index => $event){
					if($event_count<3){
						if($event["access"] == "LOGGED_IN"){
								$eventStr .= run("templates:draw", array(
																			'context' => 'loggedinevent',
																			'title' => $event["title"],
																			'url' => "",
																			)
														);
							}else if($event["access"] == "user1"){
								$eventStr .= run("templates:draw", array(
																			'context' => 'privateevent',
																			'title' => $event["title"],
																			'url' => "",
																			)
														);
							}else{
								$eventStr .= run("templates:draw", array(
																			'context' => 'publicevent',
																			'title' => $event["title"],
																			'url' => "",
																			)
														);
							}
					}else{
						if((count($events[$day_count]) - 3) > 1)
							$more_events = $more_events . "s";
							
						$eventStr .="<a style=\"font-size:smaller; top-margin:5px; bottom-margin:0px\" href=\" \">" . (count($events[$day_count]) - 3) . "&nbsp;{$more_events}</a>";
						$more_events = substr($more_events, 0, strlen($more_events)-1);
						break;
					}
					$event_count++;
				}
			}
			
			$body .= "<td valign='top' height='100px' style='border: solid #000000; border-width: 1px;'>";
			
			$body .= run("templates:draw", array(
								'context' => 'datelink',
								'url' => url . "",
								'date' => $day_count
							)
							);
							
			$day_count++;
			$body .= $eventStr;
			$body .= "</td>"; 
		}else
			$body .= "<td height='100px'>&nbsp;</td>";
	}
	$body .= "</tr></table>";
					

	$run_result .= run("templates:draw", array(
													'context' => 'contentholder',
													'title' => gettext("Calendar"),
													'body' => $body
											)
											);
							
?>
