<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 */

$username = get_input('u');
$email = get_input('e');
$name = get_input('n');

$admin_option = false;
if (elgg_is_admin_logged_in() && isset($vars['show_admin'])) {
	$admin_option = true;
}

?>
<div class="mtm">
	<label><?php echo elgg_echo('name'); ?></label>
	<br />
	<?php echo elgg_view('input/text', array('name' => 'name', 'value' => $name)); ?>
</div>
<div>
	<label><?php echo elgg_echo('email'); ?></label>
	<br />
	<?php echo elgg_view('input/text', array('name' => 'email', 'value' => $email)); ?>
</div>
<div>
	<label><?php echo elgg_echo('username'); ?></label>
	<br />
	<?php echo elgg_view('input/text', array('name' => 'username', 'value' => $username)); ?>
</div>
<div>
	<label><?php echo elgg_echo('password'); ?></label>
	<br />
	<?php echo elgg_view('input/password', array('name' => 'password')); ?>
</div>
<div>
	<label><?php echo elgg_echo('passwordagain'); ?></label>
	<br />
	<?php echo elgg_view('input/password', array('name' => 'password2')); ?>
</div>

<?php
// view to extend to add more fields to the registration form
echo elgg_view('register/extend');

// Add captcha hook
echo elgg_view('input/captcha');

if ($admin_option) {
	echo elgg_view('input/checkboxes', array('name' => "admin", 'options' => array(elgg_echo('admin_option'))));
}

echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('register')));
?>
<script type="text/javascript">
	$(function() {
		$('input[name=name]').focus();
	});
</script>