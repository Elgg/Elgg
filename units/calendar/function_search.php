<?php

	global $search_exclusions;
	
       $calendarEvent = gettext("Events by"); // gettext variable
       $inCategory = gettext("in the category"); // gettext variable
       $rssForEvent = gettext("RSS feed for events by"); // gettext variable
       $otherUsers = gettext("Other users with events in the category"); // gettext variable
       $otherUsers2 = gettext("Users with events in the category"); // gettext variable
	   $url = url;
	   	
	if (isset($parameter) && $parameter[0] == "calendar" || $parameter[0] == "calendarAll") {
		if ($parameter[0] == "calendar") {
			$search_exclusions[] = "calendarAll";
			$owner = (int) $_REQUEST['owner'];
			$searchline = "tagtype = 'calendar' and owner = $owner and tag = '".addslashes($parameter[1])."'";
			$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
			$searchline = str_replace("owner","tags.owner",$searchline);
			$refs = db_query("SELECT ref" .
							" FROM tags" .
							" WHERE $searchline");
			$searchline = "";
			if (sizeof($refs) > 0) {
	
				foreach($refs as $ref) {
					if ($searchline != "") {
						$searchline .= " or ";
					}
					$searchline .= "event.ident = " . $ref->ref;
				}
				$events = db_query("SELECT DISTINCT" .
								" users.name, users.username, event.title, event.date_start, event.ident, event.owner, event.description" .
								" FROM event " .
								" JOIN calendar" .
								" ON calendar.ident = event.owner" .
								" join users" .
								" on calendar.owner = users.ident" .
								" WHERE ($searchline)" .
								" ORDER BY date_start DESC");				
				$run_result .= "<h2>$calendarEvent " . stripslashes($events[0]->name) . " $inCategory '".$parameter[1]."'</h2>\n<ul>";
				if (sizeof($events) > 0 && $events != false) {
				foreach($events as $event) {
					$run_result .= "<li>";
					$calendarusername = run("calendar:get_id_from_owner",$event->owner);
					
										
					//TODO: HAVE TO MAKE THIS GO TO THE RIGHT PAGE
					$run_result .= "<a href=\"" . url . "_calendar/view_events.php?event_id=" . $event->ident . "\">" . gmdate("F d, Y",$event->date_start) . " - " . stripslashes($event->title) . "</a>\n";
					$run_result .= "</li>";
				}
				}
				$run_result .= "</ul>";
				$run_result .= "<p><small>[ <a href=\"". url . $calendarusername . "/calendar/rss/" . $parameter[1] . "\">$rssForEvent " . $calendarusername . " $inCategory '".$parameter[1]."'</a> ]</small></p>\n";
			}
		}
		$searchline = "tagtype = 'calendar' and tag = '".addslashes($parameter[1])."'";
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
		$searchline = str_replace("owner","tags.owner",$searchline);
		$sql = "SELECT DISTINCT users.*" .
				" FROM tags" .
				" LEFT JOIN users" .
				" ON users.ident = tags.owner" .
				" WHERE ($searchline)";
		if ($parameter[0] == "calendar") {
			$sql .= " and users.ident != " . $owner;
		}
		$users = db_query($sql);
		
		if (sizeof($users) > 0) {
			if ($parameter[0] == "calendar") {
				$run_result .= "<h2>$otherUsers '".$parameter[1]."'</h2>\n";
			} else {
				$run_result .= "<h2>$otherUsers2 '".$parameter[1]."'</h2>\n";
			}
			$body = "<table><tr>";
			$i = 1;
			foreach($users as $key => $info) {
				if ($info->icon != -1) {
					$icon = db_query("SELECT filename" .
									" FROM icons" .
									" WHERE ident = " . $info->icon .
									" AND owner = " . $info->ident);
					if (sizeof($icon) == 1) {
						$icon = $icon[0]->filename;
					} else {
						$icon = "default.png";
					}
				} else {
					$icon = "default.png";
				}
				list($width, $height, $type, $attr) = getimagesize(path . "_icons/data/" . $icon);
				if (sizeof($users) > 4) {
					$width = round($width / 2);
					$height = round($height / 2);
				}
				$friends_userid = $info->ident;
				$friends_name = htmlentities(stripslashes($info->name));
				$friends_menu = run("users:infobox:menu",array($info->ident));
				$link_keyword = urlencode($parameter[1]);
		        $width = round($width / 2);
				$height = round($height / 2);
				$body .= <<< END
				<td align="center">
		                    <p>
					<a href="{$url}search/index.php?event={$link_keyword}&owner={$friends_userid}">
					<img src="{$url}_icons/data/{$icon}" width="{$width}" height="{$height}" alt="{$friends_name}" border="0" /></a><br />
					<span class="userdetails">
						{$friends_name}
						{$friends_menu}
					</span>
		                    </p>
				</td>
END;
				if ($i % 5 == 0) {
					$body .= "\n</tr><tr>\n";
				}
				$i++;
			}
			$body .= "</tr></table>";
			$run_result .= $body;
		}		
	}
?>