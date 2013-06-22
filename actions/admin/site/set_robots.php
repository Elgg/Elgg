<?php
/**
 * Set robots.txt action
 */

$content = get_input('text');

$site = elgg_get_site_entity();

if ($site->setPrivateSetting('robots.txt', $content)) {
	system_message(elgg_echo('save:success'));
} else {
	register_error(elgg_echo('save:fail'));
}
