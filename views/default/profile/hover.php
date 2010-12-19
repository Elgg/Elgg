<?php


$user = $vars['entity'];
$username = $vars['username'];
$name = $vars['name'];

?>

<div class="avatar_menu_button"><img src="<?php echo elgg_get_site_url(); ?>_graphics/spacer.gif" border="0" width="15" height="15" /></div>
<div class="sub_menu">
	<h3 class="displayname">
		<a href="<?php echo $user->getURL(); ?>">
			<?php echo $name; ?>
			<span class="username">
				<?php echo "&#64;" . $username; ?>
			</span>
		</a>
	</h3>
	<ul class='sub_menu_list'>
<?php
if (isloggedin()) {
	// if not looking at your own avatar menu
	if ($user->getGUID() != get_loggedin_userid()) {

		// Add / Remove friend link
		$friendlinks = elgg_view('profile/menu/friendlinks',$vars);
		if (!empty($friendlinks)) {
			echo "<li class='user_menu_profile'>{$friendlinks}</li>";
		}
		// view for plugins to extend
		echo elgg_view('profile/menu/links',$vars);
	} else {
		// if looking at your own avatar menu - provide a couple of handy links
?>
		<li class="user_menu_profile">
			<a class="edit_profile" href="<?php echo elgg_get_site_url()?>pg/profile/<?php echo $username; ?>/edit/details">
				<?php echo elgg_echo("profile:edit"); ?>
			</a>
		</li>
		<li class="user_menu_profile">
			<a class="edit_avatar" href="<?php echo elgg_get_site_url()?>pg/profile/<?php echo $username; ?>/edit/icon">
				<?php echo elgg_echo("profile:editicon"); ?>
			</a>
		</li>
<?php
	}

	// if Admin is logged in, and not looking at admins own avatar menu
	if (isadminloggedin() && get_loggedin_userid() != $user->guid) {
		$params = array(
			'user' => $user,
			'sort_by' => 'order',
		);
		$admin_links = elgg_view_menu('user_admin', $params);
		if (!empty($admin_links)) {
			echo "<li class='user_menu_admin'>{$admin_links}</li>";
		}
	}
}
?>
	</ul>
</div>