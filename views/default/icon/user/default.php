<?php
/**
 * Elgg user icon
 *
 * Rounded avatar corners - CSS3 method
 * uses avatar as background image so we can clip it with border-radius in supported browsers
 *
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
 * @uses $vars['size']   The size - tiny, small, medium or large. (medium)
 * @uses $vars['hover']  Display the hover menu? (true)
 * @uses $vars['class']  Optional class added to the .elgg-avatar div
 */

$user = elgg_extract('entity', $vars, elgg_get_logged_in_user_entity());
$size = elgg_extract('size', $vars, 'medium');
if (!in_array($size, array('topbar', 'tiny', 'small', 'medium', 'large', 'master'))) {
	$size = 'medium';
}

$class = "elgg-avatar elgg-avatar-$size";
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

if (!($user instanceof ElggUser)) {
	return true;
}

$name = htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8');
$username = $user->username;

$icontime = $user->icontime;
if (!$icontime) {
	$icontime = "default";
}

$js = elgg_extract('js', $vars, '');

$hover = elgg_extract('hover', $vars, true);
if (isset($vars['override'])) {
	elgg_deprecated_notice("Use 'hover' rather than 'override' with user avatars", 1.8, 5);
	$hover = false;
}

$spacer_url = elgg_get_site_url() . '_graphics/spacer.gif';

$icon_url = $user->getIconURL($size);
$icon = "<img src=\"$spacer_url\" alt=\"$name\" title=\"$name\" $js style=\"background: url($icon_url) no-repeat;\" />";

$show_menu = $hover && (elgg_is_admin_logged_in() || !$user->isBanned());

?>
<div class="<?php echo $class; ?>">
<?php

if ($show_menu) {
	$params = array(
		'entity' => $user,
		'username' => $username,
		'name' => $name,
	);
	echo elgg_view_icon('hover-menu');
	echo elgg_view_menu('user_hover', $params);
}

echo elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => $icon,
	'is_trusted' => true,
));
?>
</div>
