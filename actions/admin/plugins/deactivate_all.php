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
		'limit' => false,
	]);
}

if (empty($plugins)) {
	return elgg_ok_response();
}

$errors = [];
foreach ($plugins as $plugin) {
	if (!$plugin->isActive()) {
		continue;
	}
	
	try {
		if (!$plugin->deactivate()) {
			$errors[] = elgg_echo('admin:plugins:deactivate:no', [$plugin->getDisplayName()]);
		}
	} catch (\Elgg\Exceptions\PluginException $e) {
		$errors[] = elgg_echo('admin:plugins:deactivate:no_with_msg', [$plugin->getDisplayName(), $e->getMessage()]);
	}
}

if (empty($errors)) {
	return elgg_ok_response();
}

foreach ($errors as $error) {
	register_error($error);
}

return elgg_error_response();
