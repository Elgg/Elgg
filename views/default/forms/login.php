<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<div>
	<label><?php echo elgg_echo('loginusername'); ?></label>
	<?php echo elgg_view('input/text', array('internalname' => 'username')); ?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label>
	<?php echo elgg_view('input/password', array('internalname' => 'password')); ?>
</div>

<?php echo elgg_view('login/extend'); ?>

<div>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>

	<label class="right mtm">
		<input type="checkbox" name="persistent" value="true" />
		<?php echo elgg_echo('user:persistent'); ?>
	</label>
	
	<?php 
	if ($vars['returntoreferer']) { 
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	}
	?>
</div>

<ul class="elgg-menu elgg-menu-footer">
<?php
	if (elgg_get_config('allow_registration')) {
		echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'pg/register/">' . elgg_echo('register') . '</a></li>';
	}
?>
	<li><a class="forgotten_password_link" href="<?php echo elgg_get_site_url(); ?>pages/account/forgotten_password.php">
		<?php echo elgg_echo('user:password:lost'); ?>
	</a></li>
</ul>