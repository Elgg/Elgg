<?php

use Elgg\Exceptions\Http\BadRequestException;

$session = elgg_get_session();
$email = (string) $session->get('emailsent', '');
if (!elgg_is_valid_email($email)) {
	throw new BadRequestException();
}

$session->remove('emailsent');

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$title = elgg_echo('uservalidationbyemail:emailsent', [$email]);

echo elgg_view_page(strip_tags($title), [
	'title' => $title,
	'content' => elgg_echo('uservalidationbyemail:registerok'),
	'sidebar' => false,
	'filter_id' => 'uservalidationbyemail',
	'filter_value' => 'emailsent',
], $shell);
