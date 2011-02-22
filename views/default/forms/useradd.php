<?php
/**
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 * 
 */

$admin_option = false;
if ((elgg_get_logged_in_user_entity()->isAdmin()) && ($vars['show_admin'])) {
	$admin_option = true;
}
?>
<div>
	<label><?php echo elgg_echo('name');?></label><br />
	<?php echo elgg_view('input/text' , array('name' => 'name')); ?>
</div>
<div>
	<label><?php echo elgg_echo('username'); ?></label><br />
	<?php echo elgg_view('input/text' , array('name' => 'username')); ?>
</div>
<div>
	<label><?php echo elgg_echo('email'); ?></label><br />
	<?php elgg_view('input/text' , array('name' => 'email')); ?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label><br />
	<?php elgg_view('input/password' , array('name' => 'password')); ?>
</div>
<div>
	<label><?php echo elgg_echo('passwordagain'); ?></label><br />
	<?php elgg_view('input/password' , array('name' => 'password2')); ?>
</div>

<?php 
if ($admin_option) {
	echo "<div>";
	echo elgg_view('input/checkboxes', array('name' => "admin", 'options' => array(elgg_echo('admin_option'))));
	echo "</div>";
}
?>

<div>
	<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('register'))); ?>
</div>