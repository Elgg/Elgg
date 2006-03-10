<?php

	global $days_of_week;
	global $calendar_id;
	global $metatags;
	global $months;
	
	$days_of_week = array(gettext("Sunday"), gettext("Monday"), gettext("Tuesday"), gettext("Wednesday"),
						  gettext("Thursday"), gettext("Friday"), gettext("Saturday"));
	
	$calendar_id = run("calendar:get_id_from_owner", array($_SESSION["userid"]));
	
	$months = array("January" => gettext("January"),
					"February" => gettext("February"),
					"March" => gettext("March"),
					"April" => gettext("April"),
					"May" => gettext("May"),
					"June" => gettext("June"),
					"July" => gettext("July"),
					"August" => gettext("August"),
					"September" => gettext("September"),
					"October" => gettext("October"),
					"November" => gettext("November"),
					"December" => gettext("December"));
					
	
	//will set the language of the calendar based on the language of elgg
	$language = locale;
	$url = url;
	$metatags .= <<< END
	<link rel="stylesheet" type="text/css" media="all" href="{$url}_calendar/jscalendar/skins/aqua/theme.css" title="Aqua" /><!-- main calendar program -->
	<script type="text/javascript" src="{$url}_calendar/jscalendar/calendar.js"></script><!-- language for the calendar -->
	<script type="text/javascript" src="{$url}_calendar/jscalendar/lang/calendar-$language.js"></script><!-- the following script defines the Calendar.setup helper function, which makes adding a calendar a matter of 1 or 2 lines of code. -->
	<script type="text/javascript" src="{$url}_calendar/jscalendar/calendar-setup.js"></script>
END;
?>