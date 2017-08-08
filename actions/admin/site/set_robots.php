<?php
/**
 * Set robots.txt action
 */

$content = get_input('text');

$site = elgg_get_site_entity();

if (!$site->setPrivateSetting('robots.txt', $content)) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('save:success'));
