<?php
/**
 * Header for main module
 *
 * @uses string $vars['type'] The section type.  Should be the same as the page handler.  Used for generating URLs.
 */

$type = $vars['type'];
$username = get_loggedin_user()->username;

$title = elgg_echo($type);

$new_button = '';
if (isloggedin()) {
	$new_link = elgg_get_array_value('new_link', $vars, "pg/$type/$username/new");
	$params = array(
		'href' => $new_link = elgg_normalize_url($new_link),
		'text' => elgg_echo("$type:new"),
		'class' => 'action-button right',
	);
	$new_button = elgg_view('output/url', $params);
}

echo <<<HTML
<h2 class="elgg-module-heading">$title</h2>
$new_button
HTML;
