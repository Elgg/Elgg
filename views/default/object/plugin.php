<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 */

$plugin = elgg_extract('entity', $vars);
if (!($plugin instanceof \ElggPlugin)) {
	return;
}

if (!elgg_in_context('admin')) {
	echo elgg_view('object/default', $vars);
	return;
}
if (!$plugin->isValid()) {
	echo elgg_view('object/plugin/invalid', $vars);
	return;
}

echo elgg_view('object/plugin/full', $vars);
