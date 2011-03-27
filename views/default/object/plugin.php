<?php
/**
 * Used to show plugin user settings.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 *
 */

$plugin = $vars['entity'];

if (!$plugin->isValid()) {
	echo elgg_view('object/plugin/invalid', $vars);
} elseif ($vars['full_view']) {
	echo elgg_view('object/plugin/advanced', $vars);
} else {
	echo elgg_view('object/plugin/simple', $vars);
}
