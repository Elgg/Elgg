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
<p class="mtm">
	<label><?php echo elgg_echo('name'); ?></label>
	<br />
	<?php echo elgg_view('input/text', array('internalname' => 'name', 'value' => $name)); ?>
</p>
<p>
	<label><?php echo elgg_echo('email'); ?></label>
	<br />
	<?php echo elgg_view('input/text', array('internalname' => 'email', 'value' => $email)); ?>
</p>
<p>
	<label><?php echo elgg_echo('username'); ?></label>
	<br />
	<?php echo elgg_view('input/text', array('internalname' => 'username', 'value' => $username)); ?>
</p>
<p>
	<label><?php echo elgg_echo('password'); ?></label>
	<br />
	<?php echo elgg_view('input/password', array('internalname' => 'password')); ?>
</p>
<p>
	<label><?php echo elgg_echo('passwordagain'); ?></label>
	<br />
	<?php echo elgg_view('input/password', array('internalname' => 'password2')); ?>
</p>

<?php
// view to extend to add more fields to the registration form
echo elgg_view('register/extend');

// Add captcha hook
echo elgg_view('input/captcha');

if ($admin_option) {
	echo elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
}

echo elgg_view('input/hidden', array('internalname' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('internalname' => 'invitecode', 'value' => $vars['invitecode']));
echo elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('register')));
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('input[name=name]').focus();
	});
</script>