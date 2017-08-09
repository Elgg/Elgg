<?php
/**
 * Disable all specified installed plugins.
 *
 * Specified plugins in the mod/ directory are disabled and the views cache and simplecache
 * are reset.
 */

$guids = get_input('guids');

if (empty($guids)) {
	$plugins = elgg_get_plugins('active');
} else {
	$plugins = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'plugin',
		'guids' => explode(',', $guids),
		'limit' => false
	]);
}

if (empty($plugins)) {
	return elgg_ok_response();
}

foreach ($plugins as $plugin) {
	if (!$plugin->isActive()) {
		continue;
	}
	
	if (!$plugin->deactivate()) {
		$msg = $plugin->getError();
		$string = ($msg) ? 'admin:plugins:deactivate:no_with_msg' : 'admin:plugins:deactivate:no';
		
		return elgg_error_response(elgg_echo($string, [$plugin->getDisplayName(), $msg]));
	}
}

return elgg_ok_response();
