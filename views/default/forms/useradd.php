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

$form_body  = "<div><label>" . elgg_echo('name') . "</label><br />" . elgg_view('input/text' , array('name' => 'name')) . "</div>";
$form_body .= "<div><label>" . elgg_echo('username') . "</label><br />" . elgg_view('input/text' , array('name' => 'username')) . "</div>";
$form_body .= "<div><label>" . elgg_echo('email') . "</label><br />" . elgg_view('input/text' , array('name' => 'email')) . "</div>";
$form_body .= "<div><label>" . elgg_echo('password') . "</label><br />" . elgg_view('input/password' , array('name' => 'password')) . "</div>";
$form_body .= "<div><label>" . elgg_echo('passwordagain') . "</label><br />" . elgg_view('input/password' , array('name' => 'password2')) . "</div>";
$form_body .= "<div>";

if ($admin_option) {
	$form_body .= elgg_view('input/checkboxes', array('name' => "admin", 'options' => array(elgg_echo('admin_option'))));
	$form_body .= '</div><div>';
}

$form_body .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('register'))) . "</div>";
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