<?php

$actions = elgg_extract('actions', $vars);
/* @var ElggMenuItem[] $actions */

if (!elgg_is_logged_in() || !$actions) {
	return;
}

$lis = [];
foreach ($actions as $action) {
	$item = elgg_view_menu_item($action, [
		'class' => 'elgg-button elgg-button-action',
	]);

	$lis[] = elgg_format_element('li', ['class' => $action->getItemClass()], $item);
}

$attrs = [
	'class' => 'elgg-menu profile-action-menu mvm',
];
echo elgg_format_element('ul', $attrs, implode('', $lis));
