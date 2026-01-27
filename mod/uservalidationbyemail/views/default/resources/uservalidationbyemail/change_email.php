<?php

use Elgg\Exceptions\Http\BadRequestException;

elgg_signed_request_gatekeeper();

$user = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
	return get_user((int) get_input('guid'));
});

if (!$user instanceof \ElggUser) {
	throw new BadRequestException();
}

echo elgg_view_page(elgg_echo('uservalidationbyemail:change_email'), [
	'content' => elgg_view_form('uservalidationbyemail/change_email', [], ['user' => $user]),
	'sidebar' => false,
	'filter' => false,
], 'walled_garden');
