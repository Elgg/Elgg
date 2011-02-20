<?php
/**
 * Elgg 2011010401 upgrade 00
 * custom_profile_fields
 *
 * Migrate 1.7 style custom profile fields to 1.8
 */

$plugin = elgg_get_plugin_from_id('profile');

// plugin not installed
if (!$plugin) {
	return true;
}

$settings = $plugin->getAllSettings();
// no fields to migrate
if (!$settings['user_defined_fields']) {
	return true;
}

$order = array();
$remove_settings = array();

// make sure we have a name and type
foreach ($settings as $k => $v) {
	if (!preg_match('/admin_defined_profile_([0-9]+)/i', $k, $matches)) {
		continue;
	}

	$i = $matches[1];
	$type_name = "admin_defined_profile_type_$i";
	$type = elgg_extract($type_name, $settings, null);

	if ($type) {
		// field name
		elgg_save_config($k, $v);
		// field value
		elgg_save_config($type_name, $type);

		$order[] = $i;
		$remove_settings[] = $k;
		$remove_settings[] = $type_name;
	}
}

if ($order) {
	// these will always need to be in order, but there might be gaps
	ksort($order);

	$order_str = implode(',', $order);
	elgg_save_config('profile_custom_fields', $order_str);

	foreach ($remove_settings as $name) {
		$plugin->unsetSetting($name);
	}

	$plugin->unsetSetting('user_defined_fields');
}