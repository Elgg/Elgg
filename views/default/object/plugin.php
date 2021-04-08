<?php
/**
 * Used to show plugin user settings.
 */

$plugin = elgg_extract('entity', $vars);
if (!($plugin instanceof \ElggPlugin)) {
	return;
}

if (!elgg_in_context('admin')) {
	echo elgg_view('object/default', $vars);
	return;
}

try {
	$plugin->assertValid();
} catch (\Elgg\Exceptions\PluginException $e) {
	$vars['error'] = $e->getMessage();
	echo elgg_view('object/plugin/invalid', $vars);
	return;
}

echo elgg_view('object/plugin/full', $vars);
