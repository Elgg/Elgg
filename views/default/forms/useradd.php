<?php
/**
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 * 
 * @todo FIXME Forms 1.8: views in the forms/ directory should not be generating the <form> wrapper itself
 */

$admin_option = false;
if ((elgg_get_logged_in_user_entity()->isAdmin()) && ($vars['show_admin'])) {
	$admin_option = true;
}

$form_body  = "<p><label>" . elgg_echo('name') . "</label><br />" . elgg_view('input/text' , array('internalname' => 'name')) . "</p>";
$form_body .= "<p><label>" . elgg_echo('username') . "</label><br />" . elgg_view('input/text' , array('internalname' => 'username')) . "</p>";
$form_body .= "<p><label>" . elgg_echo('email') . "</label><br />" . elgg_view('input/text' , array('internalname' => 'email')) . "</p>";
$form_body .= "<p><label>" . elgg_echo('password') . "</label><br />" . elgg_view('input/password' , array('internalname' => 'password')) . "</p>";
$form_body .= "<p><label>" . elgg_echo('passwordagain') . "</label><br />" . elgg_view('input/password' , array('internalname' => 'password2')) . "</p>";
$form_body .= "<p>";

if ($admin_option) {
	$form_body .= elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
	$form_body .= '</p><p>';
}

$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('register'))) . "</p>";
?>

<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('adduser'); ?></h3>
	</div>
	<div class="elgg-body">
		<?php
			echo elgg_view('input/form', array(
				'action' => "action/useradd",
				'body' => $form_body,
			));
		?>
	</div>
</div>