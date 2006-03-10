<?php
	global $page_owner;
	
	if (logged_on && $page_owner != $_SESSION['userid']) {
			 
	$title = gettext("Calendar");
	$events = gettext("Calendar Events");
	$url = url;
	$user_type = strtolower(run("users:type:get", $page_owner));
	
	$username = stripslashes(run("users:id_to_name", $page_owner));
	
	if($user_type == "person"){
	$body = <<<END
		<p align="center">
			<a href="{$url}_calendar/view_events.php?friend_id=$page_owner">$events</a> (<a href="{$url}{$username}/calendar/rss">RSS</a>)<br />
		</p>
END;
	}else if($user_type == "community"){
		$body = <<<END
		<p>
			<a href="{$url}_calendar/view_events.php?community_id=$page_owner">$events</a> (<a href="{$url}/_calendar/rss">RSS</a>)<br />
		</p>
END;
	}

	$run_result .= run("templates:draw", array(
										'context' => 'sidebarholder',
										'title' => $title,
										'body' => $body,
										'submenu' => ''
									)
									);
	}
?>
