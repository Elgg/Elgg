<?php
/**
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$admin_option = false;
if (($_SESSION['user']->isAdmin()) && ($vars['show_admin'])) {
	$admin_option = true;
}

$form_body = "<p><label>" . elgg_echo('name') . "<br />" . elgg_view('input/text' , array('internalname' => 'name')) . "</label></p>";

$form_body .= "<p><label>" . elgg_echo('email') . "<br />" . elgg_view('input/text' , array('internalname' => 'email')) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text' , array('internalname' => 'username')) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('password') . "<br />" . elgg_view('input/password' , array('internalname' => 'password')) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('passwordagain') . "<br />" . elgg_view('input/password' , array('internalname' => 'password2')) . "</label></p>";

if ($admin_option) {
	$form_body .= "<p>" . elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
}

$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('register'))) . "</p>";
?>

<div id="add-box">
<h2><?php echo elgg_echo('adduser'); ?></h2>
	<?php echo elgg_view('input/form', array('action' => "{$vars['url']}action/useradd", 'body' => $form_body)) ?>
</div>