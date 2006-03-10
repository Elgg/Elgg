<?php

	//	ELGG weblog RSS 2.0 page

	// Run includes
		require("../includes.php");
		
		run("profile:init");
		run("friends:init");
		run("calendar:init");
		
		global $profile_id;
		global $individual;
		global $page_owner;
		
		$individual = 1;
		
		$sitename = htmlentities(sitename);
		
		header("Content-type: text/xml");
		
		if (isset($page_owner)) {
			$user_id = run("users:name_to_id", $_REQUEST["username"]);
			$calendar_id = run("calendar:get_id_from_owner", array($user_id));
			
			echo <<< END
<?xml version="1.0" encoding="UTF-8"?>
<rss version='2.0' xmlns:dc='http://purl.org/dc/elements/1.1/'>
END;
			$info = db_query("SELECT * " .
							"FROM users " .
							"WHERE ident = $user_id");
			if (sizeof($info) > 0) {
				$info = $info[0];
				$name = stripslashes($info->name);
				$username = stripslashes($info->username);
				$mainurl = url . $username . "/calendar/";
				
				$calendar_description = gettext("The calendar for $name, hosted on $sitename.");
				$calendar = gettext("Calendar");
				
				echo <<< END
	<channel xml:base='$mainurl'>
		<title>$name : $calendar</title>
		<description><![CDATA[$calendar_description]]></description>
		<language>en-gb</language>
		<link>$mainurl</link>
END;
				if (!isset($_REQUEST['tag'])) {
					$entries = db_query("select * from event where owner = {$calendar_id} " .
																			"AND access = 'PUBLIC' " .
																			"ORDER BY date_start DESC " .
																			"LIMIT 10");
				} else {
					
					$tag = trim($_REQUEST['tag']);
					$entries = db_query("SELECT event.* " .
										"FROM tags " .
										"LEFT JOIN event ON event.ident = tags.ref " .
										"WHERE event.owner = {$calendar_id} " .
										"AND event.access = 'PUBLIC' " .
										"AND tags.tag = '{$tag}' " .
										"AND tags.tagtype = 'calendar' " .
										"ORDER BY date_start DESC " .
										"LIMIT 10");
				}
				
				if (sizeof($entries) > 0) {
					foreach($entries as $entry) {
						$title = stripslashes($entry->title);
						$link = url . $username . "/calendar/" . $entry->ident . ".html";
						$body = stripslashes($entry->description);
						$start_date = gmdate("D, d M Y H:i:s T", $entry->date_start);
						$end_date = gmdate("D, d M Y H:i:s T", $entry->date_end);
						$location = $entry->location;
						$keywords = db_query("SELECT * " .
											"FROM tags " .
											"WHERE tagtype = 'calendar' " .
											"AND ref = '".$entry->ident."'");
						$keywordtags = "";
						if (sizeof($keywords) > 0) {
							foreach($keywords as $keyword) {
								$keywordtags .= "\n\t\t<dc:subject><![CDATA[". stripslashes($keyword->tag) . "]]></dc:subject>";
							}
						}
						echo <<< END
		<item>
			<title><![CDATA[$title]]></title>
			<link>$link</link>
			<startDate>$start_date</startDate>
			<endDate>$end_date</endDate>
			<location><![CDATA[$location]]></location>
			$keywordtags
			<description><![CDATA[$body]]></description>
		</item>
END;
					}
				}
				echo <<< END
	</channel>
</rss>
END;
		}
	}