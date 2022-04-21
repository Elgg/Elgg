<?php

use Elgg\Exceptions\Http\BadRequestException;

elgg_signed_request_gatekeeper();

$user = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
	return get_user((int) get_input('guid'));
});

if (!$user instanceof \ElggUser) {
	throw new BadRequestException();
}

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$content = elgg_view_form('uservalidationbyemail/change_email', [], ['user' => $user]);

echo elgg_view_page(elgg_echo('uservalidationbyemail:change_email'), [
	'content' => $content,
	'sidebar' => false,
	'filter' => false,
], $shell);
