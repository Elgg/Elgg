<?php

$user = elgg_extract('entity', $vars);
if (!$user instanceof ElggUser) {
	return;
}

$checkbox = elgg_view('input/checkbox', [
	'name' => 'user_guids[]',
	'value' => $user->guid,
	'default' => false,
	'id' => "unvalidated-user-{$user->guid}",
]);

$menu = elgg_view_menu('user:unvalidated', [
	'entity' => $user,
	'class' => 'elgg-menu-hz',
]);

$title = [];
$title[] = elgg_format_element('span', ['title' => elgg_echo('username')], "{$user->username}:");
$title[] = elgg_format_element('span', ['title' => elgg_echo('name')], "\"{$user->getDisplayName()}\"");
$title[] = elgg_format_element('span', ['title' => elgg_echo('email')], "&lt;{$user->email}&gt;");

$title = elgg_format_element('label', ['for' => "unvalidated-user-{$user->guid}"], implode(' ', $title));

$subtitle = [];
$subtitle[] = elgg_echo('admin:users:unvalidated:registered', [elgg_view_friendly_time($user->time_created)]);

$params = [
	'title' => $title,
	'metadata' => $menu,
	'subtitle' => implode(' ', $subtitle),
];
$params = $params + $vars;

$content = elgg_view('user/elements/summary', $params);

echo elgg_view_image_block($checkbox, $content);
