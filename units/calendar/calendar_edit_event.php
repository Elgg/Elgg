<?php
	//id of event to be edited
	$event_id = $parameter[0];
	$event = null;
	
	global $calendar_id;
	$url = url;
	
	if(count($messages)==0){
		$event = run("calendar:get_event", array($event_id));
		$tags = run("calendar:get_event_tags", array($event_id));
	}
	
	$sectionTitle = gettext("Edit A Calendar Event");
	$eventTitle = gettext("Event title:");
	$startDate = gettext("Start&nbsp;Date");
	$endDate = gettext("End&nbsp;Date");
	$event_location = gettext("Event Location:");
	$eventDescription = gettext("Event Description:");
	$Keywords = gettext("Keywords (Separated by commas):"); // gettext variable
	$keywordDesc = gettext("Keywords commonly referred to as 'Tags' are words that represent the event post you have just made. This will make it easier for others to search and find your event."); // gettext variable
	$accessRes = gettext("Access restrictions:"); // gettext variable
	$postButton = gettext("Save Event"); // gettext
	
	$date = getdate(time());
	
	//gettext for date picker text
	$DPC_TODAY_TEXT = gettext("today");
	$DPC_BUTTON_TITLE = gettext("Open calendar...");
	$DPC_MONTH_NAMES = gettext("['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']");
	$DPC_DAY_NAMES = gettext("['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']");
	
	$request = $_SESSION["request"];
	unset($_SESSION["request"]);
	
	
	$body = <<< END
	<script type="text/javascript" language="javascript" src="scw.js"></script>
	<form method="post" name="elggform" action="{$url}/_calendar/index.php" onsubmit="return submitForm();">
	<input type="hidden" id="DPC_TODAY_TEXT" value="$DPC_TODAY_TEXT" />
	<input type="hidden" id="DPC_BUTTON_TITLE" value="$DPC_BUTTON_TITLE" />
	<input type="hidden" id="DPC_MONTH_NAMES" value="$DPC_MONTH_NAMES" />
	<input type="hidden" id="DPC_DAY_NAMES" value="$DPC_DAY_NAMES" />
	<?php //had to do this for IE not sure why ?>
	<script language="JavaScript" type="text/javascript">
		if (navigator.appName.toString().toLowerCase().indexOf("internet explorer") != -1) {
			document.write('<input type="hidden" id="DPC_BUTTON_OFFSET_Y" value="-4" />');
			document.write('<input type="hidden" id="DPC_BUTTON_OFFSET_X" value="10" />');
		}
	</script>
	<h2>$sectionTitle</h2>
END;
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $eventTitle,
								'contents' => run("display:input_field",array("event_title",$request ? $request["event_title"] : $event[0]->title,"text"))
							)
							);
	
	$startEndDate = run("calendar:display:dates", ($request ? array($request["start_date"], $request["end_date"]) : array($event[0]->date_start, $event[0]->date_end)));
	
	$body .= run("templates:draw", array(
								'context' => 'dateboxvertical',
								'name1' => $startDate,
								'name2' => $endDate,
								'contents1' =>$startEndDate[0],
								'contents2' =>$startEndDate[1]
								
							)
							);
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $event_location,
								'contents' => run("display:input_field",array("event_location", $request ? $request["event_location"] : $event[0]->location, "text"))	
							)
							);
	
	
	
	//assuming here that security works and the person actually viewing this page is the owner of the post
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $eventDescription,
								'contents' => run("display:input_field",array("event_description",$request ? $request["event_description"] : $event[0]->description, "weblogtext"))
							)
							);
	
	
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $Keywords . "<br />" . $keywordDesc,
								'contents' =>  run("display:input_field",array("event_keywords",$request ? $request["event_keywords"] : $tags, "keywords", "calendar", $event[0]->ident))
							)
							);

	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $accessRes,
								'contents' => run("display:access_level_select",array("event_access",$request ? $request["event_access"] : $event[0]->access))
							)
							);

	$body .= run("weblogs:posts:add:fields",$_SESSION['userid']);
	$body .= <<< END
	<p>
		<input type="hidden" name="event_id" value="{$event_id}" />
		<input type="hidden" name="action" value="calendar:event:update" />
		<input type="hidden" name="owner_calendar_id" value="{$calendar_id}" />
		<input type="submit" value="$postButton" />
	</p>

</form>
END;

	$run_result .= $body;
?>