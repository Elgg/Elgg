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
$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$icon_sizes = elgg_get_config('icon_sizes');
if (!isset($vars['size']) || !array_key_exists($vars['size'], $icon_sizes)) {
	$vars['size'] = 'medium';
}

$name = array($user->getDisplayName());

$class = array('elgg-avatar', "elgg-avatar-{$vars['size']}");
if (isset($vars['class'])) {
	$class[] = $vars['class'];
}

if ($user->isBanned()) {
	$class[] = 'elgg-state-banned';
	$banned_text = elgg_echo('banned');
	$name[] = "($banned_text)";
}

$js = elgg_extract('js', $vars, '');
if ($js) {
	elgg_deprecated_notice("Passing 'js' to icon views is deprecated.", 1.8, 5);
}

if (isset($vars['override'])) {
	elgg_deprecated_notice("Use 'use_hover' rather than 'override' with user avatars", 1.8, 5);
	$vars['use_hover'] = false;
	unset($vars['hover']);
}

if (isset($vars['hover'])) {
	// only 1.8.0 was released with 'hover' as the key
	$vars['use_hover'] = $vars['hover'];
	unset($vars['hover']);
}

$icon = elgg_view('output/img', array(
	'src' => $user->getIconURL($size),
	'alt' => $name,
	'class' => elgg_extract('img_class', $vars),
		));

$avatar = '';

$use_hover = elgg_extract('use_hover', $vars, true);
$show_menu = $use_hover && (elgg_is_admin_logged_in() || !$user->isBanned());
if ($show_menu) {
	$avatar = elgg_view_icon('hover-menu');
	$avatar .= elgg_view_menu('user_hover', array(
		'entity' => $user,
		'username' => $user->username,
		'name' => $user->name,
	));
}

$use_link = elgg_extract('use_link', $vars, true);
if ($use_link) {
	$avatar .= elgg_view('output/url', array(
		'href' => elgg_extract('href', $vars, $user->getURL()),
		'text' => $icon,
		'title' => $name,
		'is_trusted' => true,
		'class' => elgg_extract('link_class', $vars),
	));
} else {
	$avatar .= elgg_format_element('a', array(), $icon);
}

echo elgg_format_element('div', array(
	'class' => $class,
		), $avatar);
