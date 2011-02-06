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
 */

$user = elgg_get_array_value('entity', $vars, get_loggedin_user());
$size = elgg_get_array_value('size', $vars, 'medium');
if (!in_array($size, array('topbar', 'tiny', 'small', 'medium', 'large', 'master'))) {
	$size = 'medium';
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

$js = elgg_get_array_value('js', $vars, '');

$hover = elgg_get_array_value('hover', $vars, true);

$spacer_url = elgg_get_site_url() . '_graphics/spacer.gif';

$icon_url = $user->getIconURL($size);
$icon = "<img src=\"$spacer_url\" alt=\"$name\" title=\"$name\" $js style=\"background: url($icon_url) no-repeat;\" />";

$show_menu = $hover && (isadminloggedin() || !$user->isBanned());

?>
<div class="elgg-avatar elgg-avatar-<?php echo $size; ?>">
<?php

if ($show_menu) {
	$params = array(
		'entity' => $user,
		'username' => $username,
		'name' => $name,
	);
	echo "<span class=\"elgg-icon elgg-icon-hover-menu\"></span>";
	echo elgg_view_menu('user_hover', $params);
}

echo elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => $icon,
));
?>
</div>
