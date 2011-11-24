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
if (!in_array($size, array('topbar', 'tiny', 'small', 'medium', 'large', 'master'))) {
	$size = 'medium';
}

$class = "elgg-avatar elgg-avatar-$size";
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

$use_link = elgg_extract('use_link', $vars, true);

if (!($user instanceof ElggUser)) {
	return true;
}

$name = htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8', false);
$username = $user->username;

$icontime = $user->icontime;
if (!$icontime) {
	$icontime = "default";
}

$js = elgg_extract('js', $vars, '');
if ($js) {
	elgg_deprecated_notice("Passing 'js' to icon views is deprecated.", 1.8, 5);
}

$img_class = '';
if (isset($vars['img_class'])) {
	$img_class = $vars['img_class'];
}

$use_hover = elgg_extract('use_hover', $vars, true);
if (isset($vars['override'])) {
	elgg_deprecated_notice("Use 'use_hover' rather than 'override' with user avatars", 1.8, 5);
	$use_hover = false;
}
if (isset($vars['hover'])) {
	// only 1.8.0 was released with 'hover' as the key
	$use_hover = $vars['hover'];
}

$spacer_url = elgg_get_site_url() . '_graphics/spacer.gif';

$icon_url = elgg_format_url($user->getIconURL($size));
$icon = elgg_view('output/img', array(
	'src' => $spacer_url,
	'alt' => $name,
	'title' => $name,
	'class' => $img_class,
	'style' => "background: url($icon_url) no-repeat;",
));

$show_menu = $use_hover && (elgg_is_admin_logged_in() || !$user->isBanned());

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

if ($use_link) {
	$class = elgg_extract('link_class', $vars, '');
	$url = elgg_extract('href', $vars, $user->getURL());
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $icon,
		'is_trusted' => true,
		'class' => $class,
	));
} else {
	echo "<a>$icon</a>";
}
?>
</div>
