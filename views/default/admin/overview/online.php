<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

// users online
if( (is_plugin_enabled('search')) && (is_plugin_enabled('profile')) ) {
	elgg_push_context('search');
	$users_online = get_online_users();
	elgg_pop_context();
	?>

	<div class="admin_settings members-list users_online">
		<h3><?php echo elgg_echo('admin:statistics:label:onlineusers'); ?></h3>
		<?php echo $users_online; ?>
	</div>
<?php
}
?>