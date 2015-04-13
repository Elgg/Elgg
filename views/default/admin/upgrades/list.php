<?php
/**
 * Lists all upgrades waiting to be processed
 */

elgg_require_js('elgg/admin/upgrade');

$upgrades = elgg_extract('upgrades', $vars);

$upgrade_list = '';
foreach ($upgrades as $upgrade) {
	$upgrade_list .= elgg_view('admin/upgrades/list_item', array('upgrade' => $upgrade));
}

$action_link = elgg_view('output/url', array(
	'text' => elgg_echo('upgrade'),
	'href' => 'action/admin/upgrade',
	'class' => 'elgg-button elgg-button-action hidden mtl',
	'is_action' => true,
	'id' => 'upgrade-run',
));

$site_link = elgg_view('output/url', array(
	'text' => elgg_echo('upgrade:finished'),
	'href' => 'admin',
	'class' => 'elgg-button elgg-button-action hidden float-alt mtl',
	'id' => 'upgrade-finished',
));

echo <<<HTML
	<div width="600px; margin: 100px auto;">
		<div>$upgrade_list</div>
		$action_link
		$site_link
	</div>
HTML;
