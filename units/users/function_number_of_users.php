<?php

	global $page_owner;

	if ((!logged_on) && $page_owner == -1) {

		$body = "There are ";

		$result = db_query("select count(ident) as numusers from users where active = 'yes'");
		$result = $result[0]->numusers;
		$body .= $result . " active users.<br /> (";
		
		$result = db_query("select count(ident) as numusers from users where active = 'yes' and code != ''");
		$body .= $result[0]->numusers . " logged on.)";
		
		$run_result .= run("templates:draw",array(
							'template' => -1,
							'context' => 'infobox',
							'name' => "User Statistics",
							'contents' => $body
						)
						);

	}

?>