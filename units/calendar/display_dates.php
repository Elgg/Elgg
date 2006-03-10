<?php
	$start_date = null;
	$end_date = null;
	$start_time = null;
	$end_time = null;
	$start_am_pm = null;
	$end_am_pm = null;
	
	if(count($parameter) >= 2){
		$start_date = $parameter[0];
		$end_date = $parameter[1];

		if(strpos($start_date, "-")){
			
			$start_time = substr($start_date, strpos($start_date, "[")+1, 8);
			$start_am_pm = substr($start_time, strpos($start_time, " ")+1, 3);
			$start_time = explode(":", substr($start_time, 0, 5)); 
			
			$end_time = substr($end_time, strpos($end_time, "[")+1, 8);
			$end_am_pm = substr($end_time, strpos($end_time, " ")+1, 3);
			$end_time = explode(":", substr($end_time, 0, 5));
			
			$start_date = split("-", $start_date);
			$end_date = split("-", $end_date);
			
		} else {
			$start_date = getdate($start_date);
			$end_date = getdate($end_date);
			
			if($start_date['hours'] > 12){
				$start_am_pm = "PM";
				$start_date['hours'] = $start_date['hours'] - 12;
			}else{
				$start_am_pm = "AM";
			}
			if($end_date['hours'] > 12){
				$end_am_pm = "PM";
				$end_date['hours'] = $end_date['hours'] - 12;
			}else{
				$end_am_pm = "AM";
			}
			
			if($start_date['minutes'] < 10){
				$start_date['minutes'] = "0" . $start_date['minutes'];
			}

			if($end_date['minutes'] < 10){
				$end_date['minutes'] = "0" . $end_date['minutes'];
			}
		}
	}
	
	$reset = count($parameter)==3;
	
	$javascript = '<script type="text/javascript">
						Calendar.setup({
								inputField     :    "start_date",     		// id of the input field
								ifFormat       :    "%d-%m-%Y [%I:%M %p]",  // format of the input field
								showsTime      :    true,            		// will display a time selector
								timeFormat     :    "12",
								button         :    "start_trigger_c",  	// trigger for the calendar (button ID)
								align          :    "Bl",           		// alignment (defaults to "Bl")
								singleClick    :    true
						});
						Calendar.setup({
								inputField     :    "end_date",     		// id of the input field
								ifFormat       :    "%d-%m-%Y [%I:%M %p]",  // format of the input field
								showsTime      :    true,           		// will display a time selector
								timeFormat     :    "12",
								button         :    "end_trigger_c",  		// trigger for the calendar (button ID)
								align          :    "Bl",           		// alignment (defaults to "Bl")
								singleClick    :    true
						});
					</script>';

	$toolTipStart =  gettext("start date selector");
	$toolTipEnd =  gettext("end date selector");
	
	if(!$start_date && !$end_date || $reset){
		//by default display current date for start and end date
		$today = date('d-m-Y');
		$today .= " [12:00 AM]";
		
		$run_result = array("<input value=\"" . $today .  "\" size = \"18\" maxlength=\"21\" type=\"text\" id=\"start_date\" name=\"start_date\" />&nbsp;
							<img src=\"" . url . "_calendar/jscalendar/calendar_icon.png\" id=\"start_trigger_c\" style=\"cursor: pointer;\" title=\"". $toolTipStart ."\" alt=\"calendar icon\" />",
							
							"<input value=\"" . $today .  "\" size = \"18\" maxlength=\"21\" type=\"text\" id=\"end_date\" name=\"end_date\" />&nbsp;
							<img src=\"" . url . "_calendar/jscalendar/calendar_icon.png\" id=\"end_trigger_c\" style=\"cursor: pointer;\" title=\"". $toolTipEnd ."\" alt=\"calendar icon\" />" . $javascript
						);
	} else {
		$count = count($start_date);
		
		if(isset($start_date["mday"]) && strlen($start_date["mday"]) == 1)
			$start_date["mday"] = "0" . $start_date["mday"];
		if(isset($start_date["mon"]) && strlen($start_date["mon"]) == 1)
			$start_date["mon"] = "0" . $start_date["mon"];
			
		if(isset($end_date["mday"]) && strlen($end_date["mday"]) == 1)
			$end_date["mday"] = "0" . $end_date["mday"];
		if(isset($end_date["mon"]) && strlen($end_date["mon"]) == 1)
			$end_date["mon"] = "0" . $end_date["mon"];
			
		if(!isset($start_time) && strlen($start_date["hours"]) == 1)
			$start_date["hours"] = "0" . $start_date["hours"];
			
		if(!$start_time && strlen($start_date["hours"]) == 1)
			$start_date["hours"] = "0" . $start_date["hours"];
		if(!$end_time && strlen($end_date["hours"]) == 1)
			$end_date["hours"] = "0" . $end_date["hours"];
			
		$run_result = array("<input maxlength=\"21\" size=\"18\" type=\"text\" id=\"start_date\" name=\"start_date\" value=\"". ($count==3 ? $start_date[0] : $start_date["mday"]) ."-". ($count==3 ? $start_date[1] : $start_date["mon"]) ."-". ($count==3 ? $start_date[2] : $start_date["year"]) . " [" . ($start_time ? $start_time[0] : $start_date['hours']) . ":" . ($start_time ? $start_time[1]:$start_date['minutes']) . " " . $start_am_pm .  "]" ."\" />&nbsp;
						<img src=\"" . url . "_calendar/jscalendar/calendar_icon.png\" id=\"start_trigger_c\" style=\"cursor: pointer;\" title=\"". $toolTipStart ."\" alt=\"calendar icon\" />",
						
						"<input maxlength=\"21\" size=\"18\" type=\"text\" id=\"end_date\" name=\"end_date\" value=\"". ($count==3 ? $end_date[0] : $end_date["mday"]) ."-". ($count==3 ? $end_date[1] : $end_date["mon"]) ."-". ($count==3 ? $end_date[2] : $end_date["year"]) . " [" . ($end_time ? $end_time[0] : $end_date['hours']) . ":" . ($end_time ? $end_time[1] : $end_date['minutes']) . " " . $end_am_pm .  "]" ."\" />&nbsp;
						<img src=\"" . url . "_calendar/jscalendar/calendar_icon.png\" id=\"end_trigger_c\" style=\"cursor: pointer;\" title=\"". $toolTipEnd ."\" alt=\"calendar icon\" />" . $javascript
					);
	}
	
	//java script for calendar feature
	

?>