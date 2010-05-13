<?php
// users online
if ((is_plugin_enabled('search')) && (is_plugin_enabled('profile'))) {
	get_context('search');
	$users_online = get_online_users();
	get_context('admin');
	
	echo elgg_view_title(elgg_echo('admin:users'));
	?>

	<div class="admin_settings members_list users_online">
		<h3><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h3>
		<?php echo $users_online; ?>
	</div>
<?php
}