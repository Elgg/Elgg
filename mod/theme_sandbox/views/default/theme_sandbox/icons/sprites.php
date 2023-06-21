<?php
$icons = [
	'arrow-left',
	'arrow-right',
	'arrow-circle-left',
	'arrow-circle-right',
	'arrows-alt',
	'calendar',
	'check',
	'check-circle',
	'comment',
	'comments',
	'delete',
	'delete-alt',
	'download',
	'eye',
	'facebook',
	'grid',
	'home',
	'image',
	'info',
	'link',
	'list',
	'lock',
	'mail',
	'minus-circle',
	'mobile',
	'paperclip',
	'plus-circle',
	'print',
	'refresh',
	'rss-square',
	'search',
	'settings',
	'settings-alt',
	'share',
	'shopping-cart',
	'star',
	'star-regular',
	'tag',
	'thumbs-down',
	'thumbs-up',
	'thumbtack',
	'times-circle',
	'trash',
	'twitter-square',
	'undo',
	'unlock',
	'user',
	'users',
	'video',
];

echo elgg_format_element('p', [], elgg_echo('theme_sandbox:icons:blurb'));

$lis = [];
foreach ($icons as $icon) {
	$lis[] = elgg_format_element('li', ['title' => "elgg-icon-{$icon}", 'style' => 'margin: 10px;'], elgg_view_icon($icon));
}

echo elgg_format_element('ul', ['class' => 'elgg-gallery'], implode(PHP_EOL, $lis));
