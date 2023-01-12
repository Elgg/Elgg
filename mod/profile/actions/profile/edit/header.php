<?php
/**
 * Profile header upload action
 */

$user_guid = (int) get_input('guid');
$user = get_user($user_guid);
if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('EntityNotFoundException'));
}

if (get_input('header_remove')) {
	if (!$user->deleteIcon('header')) {
		return elgg_error_response(elgg_echo('header:remove:fail'));
	}
	
	return elgg_ok_response('', elgg_echo('header:remove:success'), $user->getURL());
}

// try to save new icon, will fail silently if no icon provided
if (!$user->saveIconFromUploadedFile('header', 'header')) {
	return elgg_error_response(elgg_echo('header:upload:fail'));
}

return elgg_ok_response('', elgg_echo('header:upload:success'), $user->getURL());
