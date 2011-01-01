<?php
/**
 * Provide a way of setting your password
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner();

if ($user) {
?>
<div class="elgg-module elgg-info-module">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('user:set:password'); ?></h3>
	</div>
	<div class="elgg-body">
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
	</div>
</div>
<?php
}