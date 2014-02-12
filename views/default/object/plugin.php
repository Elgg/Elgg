<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 */

$plugin = $vars['entity'];

if (!elgg_in_context('admin')) {
	echo elgg_view('object/default', $vars);
} else {
	if (!$plugin->isValid()) {
		echo elgg_view('object/plugin/invalid', $vars);
	} else {
		echo elgg_view('object/plugin/full', $vars);
	}
}
