<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<div class='loginbox'>
	<label><?php echo elgg_echo('loginusername'); ?></label><br />
	<?php echo elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea')); ?>
	<label><?php echo elgg_echo('password'); ?></label><br />
	<?php echo elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea')); ?><br />

	<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>

	<span class='rememberme'>
		<label>
			<input type="checkbox" name="persistent" value="true" />
			<?php echo elgg_echo('user:persistent'); ?>
		</label>
	</span><br />

	<?php echo elgg_view('login/extend'); ?>

<?php
	if (elgg_get_config('allow_registration')) {
		echo '<a class="registration_link" href="' . elgg_get_site_url() . 'pg/register/">' . elgg_echo('register') . '</a> | ';
	}
?>
	<a class="forgotten_password_link" href="<?php echo elgg_get_site_url(); ?>pages/account/forgotten_password.php">
		<?php echo elgg_echo('user:password:lost'); ?>
	</a>
</div>