<?php
/**
 * Generate a new site secret
 */

$params['old'] = get_site_secret();
if (!elgg_trigger_before_event('regenerate_site_secret', 'system', null, $params)) {
	// note: provide your own message to the user if you cancel
	forward(REFERER);
}

init_site_secret();
elgg_reset_system_cache();

$params['new'] = get_site_secret();
elgg_trigger_after_event('regenerate_site_secret', 'system', null, $params);

system_message(elgg_echo('admin:site:secret_regenerated'));

forward(REFERER);
