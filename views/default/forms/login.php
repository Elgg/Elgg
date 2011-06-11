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
	<?php echo elgg_view('input/text', array('name' => 'username')); ?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label>
	<?php echo elgg_view('input/password', array('name' => 'password')); ?>
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
		echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'register">' . elgg_echo('register') . '</a></li>';
	}
?>
	<li><a class="forgotten_password_link" href="<?php echo elgg_get_site_url(); ?>forgotpassword">
		<?php echo elgg_echo('user:password:lost'); ?>
	</a></li>
</ul>