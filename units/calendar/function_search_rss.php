<?php

	global $search_exclusions;

	if (isset($parameter) && $parameter[0] == "calendar" || $parameter[0] == "calendarAll") {
		
		$search_exclusions[] = "calendarAll";
		$owner = (int) $_REQUEST['owner'];
		$searchline = "tagtype = 'calendar' and tag = '".addslashes($parameter[1])."'";
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
		$searchline = str_replace("access", "event.access", $searchline);
		$searchline = str_replace("owner", "event.owner", $searchline);
		
		$owners = db_query("SELECT DISTINCT calendar.owner FROM calendar, tags WHERE calendar.ident = tags.owner");
		$owner_str = "";
		$num_owners = count($owners);
		for ($i=0; $i < $num_owners; $i++) {
			if ($i != 0) {
				$owner_str .= "," . $owners[$i]->owner;
			} else {
				$owner_str .= $owners[$i]->owner;
			}
		}
		
		$refs = db_query("select event.owner, event.ident, event.title, users.username, tags.ref from tags left join event on event.ident = ref left join users on users.ident IN ({$owner_str}) where $searchline order by event.date_start desc limit 50");
		
		if (sizeof($refs) > 0) {
			foreach($refs as $event) {
				$run_result .= "\t<item>\n";
				$run_result .= "\t\t<title><![CDATA[" . gettext("Calendar event") . " :: " . (stripslashes($event->name));
				$run_result .= " :: " . (stripslashes($event->title));
				$run_result .= "]]></title>\n";
				$run_result .= "\t\t<link>" . url . (stripslashes($event->username)) . "/calendar/" . $event->ident . ".html</link>\n";
				$run_result .= "\t</item>\n";
			}
		}
		
	}

?>