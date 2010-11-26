<?php
/**
 * Provide a way of setting your password
 *
 * @package Elgg
 * @subpackage Core
 */

$user = page_owner_entity();

if ($user) {
?>
<h3><?php echo elgg_echo('user:set:password'); ?></h3>

	<?php
		// only make the admin user enter current password for changing his own password.
		if (!isadminloggedin() || isadminloggedin() && $user->guid == get_loggedin_userid()) {
	?>
	<p>
	<?php echo elgg_echo('user:current_password:label'); ?>:
	<?php
		echo elgg_view('input/password', array('internalname' => 'current_password'));
	?>
	</p>
	<?php } ?>

	<p>
	<?php echo elgg_echo('user:password:label'); ?>:
	<?php
		echo elgg_view('input/password', array('internalname' => 'password'));
	?>
	</p>

	<p>
	<?php echo elgg_echo('user:password2:label'); ?>: <?php
		echo elgg_view('input/password', array('internalname' => 'password2'));
	?>
	</p>

<?php
}