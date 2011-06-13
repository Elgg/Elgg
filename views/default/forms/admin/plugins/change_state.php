<?php
/**
 * Activate/deactive all plugins specified by guids array
 *
 * @uses $vars['guids']  Array of GUIDs
 * @uses $vars['action'] 'activate' or 'deactivate'
 */

$guids = elgg_extract('guids', $vars, array());
$guids = implode(',', $guids);

echo elgg_view('input/hidden', array(
	'name' => 'guids',
	'value' => $guids,
));

echo elgg_view('input/submit', array(
	'value' => elgg_echo("admin:plugins:{$vars['action']}_all"),
	'class' => 'elgg-button elgg-button-action'
));
