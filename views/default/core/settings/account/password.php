<?php
/**
 * Provide a way of setting your password
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if ($user) {
?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('user:set:password'); ?></h3>
	</div>
	<div class="elgg-body">
		<?php
			// only make the admin user enter current password for changing his own password.
			if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
		?>
		<p>
		<?php echo elgg_echo('user:current_password:label'); ?>:
		<?php
			echo elgg_view('input/password', array('name' => 'current_password'));
		?>
		</p>
		<?php } ?>

		<p>
		<?php echo elgg_echo('user:password:label'); ?>:
		<?php
			echo elgg_view('input/password', array('name' => 'password'));
		?>
		</p>

		<p>
		<?php echo elgg_echo('user:password2:label'); ?>: <?php
			echo elgg_view('input/password', array('name' => 'password2'));
		?>
		</p>
	</div>
</div>
<?php
}