<?php

if (elgg_is_logged_in()) {
	forward('');
}

$session = elgg_get_session();
$email = $session->get('emailsent', '');
if (!$email) {
	forward('');
}
$title = elgg_echo('uservalidationbyemail:emailsent', array($email));
$body = elgg_view_layout('one_column', array(
	'title' => $title,
	'content' => elgg_echo('uservalidationbyemail:registerok'),
));
echo elgg_view_page(strip_tags($title), $body);
