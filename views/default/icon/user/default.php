<?php
/**
 * Elgg user icon
 *
 * Rounded avatar corners - CSS3 method
 * uses avatar as background image so we can clip it with border-radius in supported browsers
 *
 * @uses $vars['entity']     The user entity. If none specified, the current user is assumed.
 * @uses $vars['size']       The size - tiny, small, medium or large. (medium)
 * @uses $vars['use_hover']  Display the hover menu? (true)
 * @uses $vars['use_link']   Wrap a link around image? (true)
 * @uses $vars['class']      Optional class added to the .elgg-avatar div
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class for the link
 * @uses $vars['href']       Optional override of the link href
 */

$user = elgg_extract('entity', $vars, elgg_get_logged_in_user_entity());
$size = elgg_extract('size', $vars, 'medium');

if (!($user instanceof ElggUser)) {
	return;
}

$icon_sizes = elgg_get_icon_sizes('user');
if (!array_key_exists($size, $icon_sizes)) {
	$size = 'medium';
}

$name = htmlspecialchars($user->getDisplayName(), ENT_QUOTES, 'UTF-8', false);
$username = $user->username;

$wrapper_class = [
	'elgg-avatar',
	"elgg-avatar-$size",
];
$wrapper_class = elgg_extract_class($vars, $wrapper_class);

if ($user->isBanned()) {
	$wrapper_class[] = 'elgg-state-banned';
	$name .= ' (' . elgg_echo('banned') . ')';
}

$icon = elgg_view('output/img', [
	'src' => $user->getIconURL($size),
	'alt' => $name,
	'title' => $name,
	'class' => elgg_extract_class($vars, [], 'img_class'),
]);

if (empty($icon)) {
	return;
}

$show_menu = elgg_extract('use_hover', $vars, true) && (elgg_is_admin_logged_in() || !$user->isBanned());

$content = '';

if ($show_menu) {
	$params = [
		'entity' => $user,
		'username' => $username,
		'name' => $name,
	];
	$content .= elgg_view('navigation/menu/user_hover/placeholder', ['entity' => $user]);
	
	$wrapper_class[] = 'elgg-avatar-menu';
}

if (elgg_extract('use_link', $vars, true)) {
	$content .= elgg_view('output/url', [
		'href' => elgg_extract('href', $vars, $user->getURL()),
		'text' => $icon,
		'is_trusted' => true,
		'class' => elgg_extract_class($vars, [], 'link_class'),
	]);
} else {
	$content .= elgg_format_element('a', [], $icon);
}

echo elgg_format_element('div', ['class' => $wrapper_class], $content);
