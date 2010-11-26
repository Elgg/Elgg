<?php

/**
 * Elgg riverdashboard site message sidebar box
 *
 * @package ElggRiverDash
 *
 */

//grab the current site message
$site_message = elgg_get_entities(array('types' => 'object', 'subtypes' => 'sitemessage', 'limit' => 1));
if ($site_message) {
	$mes = $site_message[0];
	$message = $mes->description;
	$dateStamp = elgg_view_friendly_time($mes->time_created);
	$delete = elgg_view("output/confirmlink",array(
			'href' => $vars['url'] . "action/riverdashboard/delete?message_guid=" . $mes->guid,
			'text' => elgg_echo('delete'),
			'confirm' => elgg_echo('deleteconfirm'),
	));
}

?>

<div class="sidebarBox">

<?php

	//if there is a site message
	if ($site_message) {

		echo "<h3>" . elgg_echo("sitemessages:announcements") . "</h3>";
		echo "<p><small>" . elgg_echo("sitemessages:posted") . ": " . $dateStamp;
		//if admin display the delete link
		if (isadminloggedin()) {
			echo " " . $delete . " ";
		}
		echo "</small></p>";
		//display the message
		echo "<p>" . $message . "</p>";

		//display the input form to add a new message
		if (isadminloggedin()) {
			//action
			$action = "riverdashboard/add";
			$link = elgg_echo("sitemessages:add");
			$input_area = elgg_view('input/plaintext', array('internalname' => 'sitemessage', 'value' => ''));
			$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
			$form_body = <<<EOT
	
			<p><a class="collapsibleboxlink">{$link}</a></p>
			<div class="collapsible_box">
					{$input_area}<br />{$submit_input}
			</div>
	
EOT;
			//display the form
			echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));
		}//end of admin if statement

		//if there is no message, add a form to create one
	} else {

		if (isadminloggedin()) {

			//action
			$action = "riverdashboard/add";
			$link = elgg_echo("sitemessages:add");
			$input_area = elgg_view('input/text', array('internalname' => 'sitemessage', 'value' => ''));
			$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
			$form_body = <<<EOT
	
			<p><a class="collapsibleboxlink">{$link}</a></p>
			<div class="collapsible_box">
					{$input_area}<br />{$submit_input}
			</div>
EOT;
			//display the form
			echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));

		}//end of admin check
	}//end of main if
?>
</div>
