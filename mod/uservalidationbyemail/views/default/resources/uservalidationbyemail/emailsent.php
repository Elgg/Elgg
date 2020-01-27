<?php

$session = elgg_get_session();
$email = $session->get('emailsent', '');
if (!$email) {
	forward();
}

$session->remove('emailsent');

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$title = elgg_echo('uservalidationbyemail:emailsent', [$email]);

echo elgg_view_page(strip_tags($title), [
	'title' => $title,
	'content' => elgg_echo('uservalidationbyemail:registerok'),
	'sidebar' => false,
], $shell);
