<?php
/**
 * Generate a new site secret
 */

init_site_secret();
elgg_reset_system_cache();

system_message(elgg_echo('admin:site:secret_regenerated'));

// if loading via ajax, send a new token to replace the stored tokens
// on the site, since we just invalidated them.
if (elgg_is_xhr()) {
	$ts = time();
	echo json_encode(array(
		'__elgg_ts' => $ts,
		'__elgg_token' => generate_action_token($ts)
	));
}

forward(REFERER);
