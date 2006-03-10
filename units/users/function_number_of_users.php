<?php

	global $page_owner;
	if ((!logged_on) && $page_owner == -1) {

		$result = db_query("select count(*) as numusers from users where active = 'yes'");
		$result = "<p>" . sprintf(gettext("There are %d active users."),$result[0]->numusers);
		$body = $result;
		$body .= "<br />";
		
		$result = db_query("select count(*) as numusers from users where active = 'yes' and code != '' and last_action > (UNIX_TIMESTAMP() - 600)");
		$body .= sprintf(gettext("(%d logged on.)"), $result[0]->numusers) . "</p>";
		
		$run_result .= "<li>";
		$run_result .= run("templates:draw",array(
							'template' => -1,
							'context' => 'sidebarholder',
							'title' => gettext("User Statistics"),
							'body' => $body,
							'submenu' => ''
						)
						);
		$run_result .= "</li>";

	}

?>