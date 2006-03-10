<?php
	$calendar_id = $parameter[0];
	$reset = $parameter[1];
	
	$sectionTitle = gettext("Add A Calendar Event");
	$eventTitle = gettext("Event title:");
	$startDate = gettext("Start&nbsp;Date");
	$endDate = gettext("End&nbsp;Date");
	$event_location = gettext("Event Location:");
	$eventDescription = gettext("Event Description:");
	$Keywords = gettext("Keywords (Separated by commas):"); // gettext variable
	$keywordDesc = gettext("Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting."); // gettext variable
	$accessRes = gettext("Access restrictions:"); // gettext variable
	$postButton = gettext("Save Event"); // gettext
	
	$date = getdate(time());
	
	//check for "request" session variable for return field information
	$request = $_SESSION["request"];
	unset($_SESSION["request"]);
		
	$body = <<< END

<form method="post" name="elggform" action="../_calendar/index.php" onsubmit="return submitForm();">
	<h2>$sectionTitle</h2>
END;
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $eventTitle,
								'contents' => run("display:input_field",array("event_title",$request ? $request["event_title"] : "","text"))
							)
							);
	
	$startEndDate = run("calendar:display:dates", $request ? array($request["start_date"], $request["end_date"], $reset) : array());
	
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
								'contents' => run("display:input_field",array("event_location", $request ? $request["event_location"] : "", "text"))
							)
							);
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $eventDescription,
								'contents' => run("display:input_field",array("event_description",$request ? $request["event_description"] : "","weblogtext"))
							)
							);
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $Keywords . "<br />" . $keywordDesc,
								'contents' =>  run("display:input_field",array("event_keywords",$request ? $request["event_keywords"] : "","keywords"))
							)
							);
	
	$body .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $accessRes,
								'contents' => run("display:access_level_select",array("event_access",$request ? $request["event_access"] : ""))
							)
							);
	
	//$body .= run("weblogs:posts:add:fields",$_SESSION['userid']);
	$body .= <<< END
	<p>
		<input type="hidden" name="action" value="calendar:event:create" />
		<input type="hidden" name="owner_calendar_id" value="{$calendar_id}" />
		<input type="submit" value="$postButton" />
	</p>

</form>
END;

	$run_result .= $body;
	
?>
