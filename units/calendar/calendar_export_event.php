<?php
	global $calendar_id;
	run("profile:init");
	
	$event_id = (int) $parameter[0];
	
	$event = run("calendar:get_event", array($event_id));
	$event = $event[0];
	$username = run("profile:display:name");
	
	$filename = "./export/" . str_replace(" ", "_", $username) . "_" .str_replace(" ", "_", $event->title) . ".ics";
	
	$start_date = getdate($event->date_start);
	$end_date = getdate($event->date_end);
	$now = getdate(time());
	
	// make contents
	$content  = "BEGIN:VCALENDAR\r\n";
	$content .= "CALSCALE:GREGORIAN\r\n";
	$content .= "X-WR-TIMEZONE;VALUE=TEXT:US/Eastern\r\n";
	$content .= "METHOD:PUBLISH\r\n";
	$content .= "PRODID:-//Apple Computer\, Inc//iCal 1.0//EN\r\n";
	$content .= "X-WR-CALNAME;VALUE=TEXT:" . $event->title . "\r\n";
	$content .= "X-WR-RELCALID;VALUE=TEXT:99732F9A-92C7-11D7-A4A2-000A95690022\r\n";
	$content .= "VERSION:2.0\r\n";
				
	$content .= "BEGIN:VEVENT\r\n";
	
	$content .= "SEQUENCE:1\r\n";	
	
	$content .= "DTSTAMP:" . $now["year"] . $now["mon"] . $now["mday"] . "T" . 
						(strlen($now["hours"]) == 1 ? "0" . $now["hours"] : $now["hours"]) . 
						(strlen($now["minutes"]) == 1 ? "0" . $now["minutes"] : $now["minutes"]) . "00Z\r\n";
						
	$content .= "UID:" . rand(0, 9999999) . "\r\n";
	
	$content .= "SUMMARY:" . stripslashes($event->title) ."\r\n";
	
	$content .= "DTSTART;TZID=US/Eastern:" . $start_date["year"] . $start_date["mon"] . $start_date["mday"] . "T" . 
						(strlen($start_date["hours"]) == 1 ? "0" . $start_date["hours"] : $start_date["hours"]) . 
						(strlen($start_date["minutes"]) == 1 ? "0" . $start_date["minutes"] : $start_date["minutes"]) . "00\r\n"; 
	
	$content .= "DTEND;TZID=US/Eastern:" . $end_date["year"] . $end_date["mon"] . $end_date["mday"] . "T" . 
						(strlen($end_date["hours"]) == 1 ? "0" . $end_date["hours"] : $end_date["hours"]) . 
						(strlen($end_date["minutes"]) == 1 ? "0" . $end_date["minutes"] : $end_date["minutes"]) . "00\r\n";
	
	if(strlen($event->location) > 0) {
		$content .= "LOCATION:" . stripslashes($event->location) ."\r\n"; 
	}
	
	if(strlen($event->description) > 0) {
		$content .= "DESCRIPTION:" . stripslashes($event->description) ."\r\n";
	}
	
	$content .= "END:VEVENT\r\n";
	$content .= "END:VCALENDAR\r\n";
	
	// get handle
	$handle = fopen($filename, "w");
	
	// write file
	if(fwrite($handle, $content) === FALSE){
		echo "Cannot write file";
		exit;
	}
	
	fclose($handle);
	
	// read file
	$ics_file = fopen($filename, "r");
	$ics_data = fread($ics_file, filesize($filename));
	
	// output file
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Length: " . strlen($contents));
	header("Content-type: application/txt");
	header("Content-Disposition: inline; filename=" . $filename);
	
	print $ics_data;
	exit;
?>