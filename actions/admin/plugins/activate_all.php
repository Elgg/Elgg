<?php
/**
 * Activates all specified installed and inactive plugins.
 *
 * All specified plugins in the mod/ directory that aren't active are activated and the views
 * cache and simplecache are invalidated.
 */

$guids = get_input('guids');

if (empty($guids)) {
	$plugins = elgg_get_plugins('inactive');
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

do {
	$additional_plugins_activated = false;
	foreach ($plugins as $key => $plugin) {
		if ($plugin->isActive()) {
			unset($plugins[$key]);
			continue;
		}
		
		if (!$plugin->activate()) {
			// plugin could not be activated in this loop, maybe in the next loop
			continue;
		}

		$ids = [
			'cannot_start' . $plugin->getID(),
			'invalid_and_deactivated_' . $plugin->getID()
		];

		foreach ($ids as $id) {
			elgg_delete_admin_notice($id);
		}

		// mark that something has changed in this loop
		$additional_plugins_activated = true;
		unset($plugins[$key]);
	}
	
	if (!$additional_plugins_activated) {
		// no updates in this pass, break the loop
		break;
	}
} while (count($plugins) > 0);

if (count($plugins) > 0) {
	foreach ($plugins as $plugin) {
		$msg = $plugin->getError();
		$string = ($msg) ? 'admin:plugins:activate:no_with_msg' : 'admin:plugins:activate:no';

		return elgg_error_response(elgg_echo($string, [$plugin->getDisplayName(), $msg]));
	}
}

return elgg_ok_response();
