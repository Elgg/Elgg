<?php
/**
 * Elgg profile icon
 * 
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed.
 * @uses $vars['align']
 * @uses $vars['override']
 * @uses $vars['js']
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

// Get any align and js
if (!empty($vars['align'])) {
	$align = " align=\"{$vars['align']}\" ";
} else {
	$align = '';
}

$override = elgg_get_array_value('override', $vars, false);

$spacer_url = elgg_get_site_url() . '_graphics/spacer.gif';
$icon_url = $user->getIcon($size);
$icon = "<img src=\"$spacer_url\" $align alt=\"$name\" title=\"$name\" $js style=\"background: url($icon_url) no-repeat;\" class=\"$size\" />";

// no hover menu if override set
if ($override) {
	echo $icon;
	return true;
}

?>	
<div class="elgg-user-icon <?php echo $size; ?>">
<?php
$params = array(
	'entity' => $user,
	'username' => $username,
	'name' => $name,
);
echo elgg_view('profile/hover', $params);

if ((isadminloggedin()) || (!$user->isBanned())) {
?>
	<a href="<?php echo $user->getURL(); ?>" class="icon" >
<?php
}

// Rounded avatar corners - CSS3 method
// users avatar as background image so we can clip it with border-radius in supported browsers
echo $icon;

if ((isadminloggedin()) || (!$user->isBanned())) {
?>
	</a>
<?php
}
?>
</div>
