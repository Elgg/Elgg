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
} else {
	echo elgg_view('object/plugin/full', $vars);
}
